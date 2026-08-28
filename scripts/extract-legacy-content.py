#!/usr/bin/env python3
"""
One-shot extractor: pulls content out of the 2017 index.html into the
Markdown and JSON files the Astro site reads.

Run once during the migration, then delete. Recover the source with:
    git show master:index.html > .migration-source.html

Usage: python3 scripts/extract-legacy-content.py .migration-source.html
"""

import json
import re
import sys
import unicodedata
from pathlib import Path

from bs4 import BeautifulSoup

SRC = Path(sys.argv[1] if len(sys.argv) > 1 else '.migration-source.html')
ROOT = Path(__file__).resolve().parent.parent
PROJECTS_DIR = ROOT / 'src' / 'content' / 'projects'
PAGES_DIR = ROOT / 'src' / 'content' / 'pages'
DATA_DIR = ROOT / 'src' / 'data'

# Old data-type values -> new category slugs.
CATEGORY_MAP = {
    '3DModeling': '3d',
    'branding': 'branding',
    'web': 'web',
    'textures': 'textures',
    'blueprints': 'blueprints',
    'tools': 'tools',
    'uxui': 'uxui',
    'cad': 'cad',
    'video': 'video',
}

CATEGORY_LABELS = {
    'games': 'Games',
    '3d': '3D Modeling',
    'branding': 'Branding',
    'web': 'Web',
    'textures': 'Texturing',
    'blueprints': 'Blueprint (Scripting)',
    'tools': 'Tools',
    'uxui': 'UX/UI',
    'cad': 'AutoCAD',
    'video': 'Video',
}

# Font Awesome 4 class -> Iconify FA6 name.
ICON_MAP = {
    'fa-film': 'fa6-solid:film',
    'fa-code': 'fa6-solid:code',
    'fa-pencil': 'fa6-solid:pencil',
    'fa-headphones': 'fa6-solid:headphones',
    'fa-globe': 'fa6-solid:globe',
    'fa-gamepad': 'fa6-solid:gamepad',
    'fa-camera': 'fa6-solid:camera',
}


def slugify(value: str) -> str:
    value = unicodedata.normalize('NFKD', value).encode('ascii', 'ignore').decode()
    value = re.sub(r'[^\w\s-]', '', value).strip().lower()
    return re.sub(r'[\s_-]+', '-', value) or 'untitled'


def clean(text: str) -> str:
    """Collapse the source's hand-wrapped indentation into single spaces."""
    return re.sub(r'\s+', ' ', (text or '')).strip()


def yaml_scalar(value) -> str:
    """JSON is valid YAML, so json.dumps gives correctly escaped scalars."""
    return json.dumps(value, ensure_ascii=False)


def asset(src: str, depth: str = '../../assets/') -> str:
    """images/portfolio/x.jpg -> ../../assets/images/portfolio/x.jpg"""
    return depth + src.lstrip('./')


def write_json(path: Path, payload) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open('w', encoding='utf-8') as fh:
        json.dump(payload, fh, indent=2, ensure_ascii=False)
        fh.write('\n')
    print(f'  {path.relative_to(ROOT)}')


soup = BeautifulSoup(SRC.read_text(encoding='utf-8', errors='replace'), 'lxml')


# --- Projects ---------------------------------------------------------
#
# The gallery holds three kinds of item, not one:
#   * image tiles     — thumbnail + lightbox image, title and blurb in markup
#   * sketchfab       — an <iframe> 3D viewer; title is in the credit line
#   * youtube         — an <iframe> video with NO title anywhere in the source
#
# YouTube titles were recovered once via the oEmbed endpoint and cached in
# scripts/youtube-titles.json so this script stays offline and repeatable.

YT_TITLES = json.loads((ROOT / 'scripts' / 'youtube-titles.json').read_text(encoding='utf-8'))


def extract_projects() -> int:
    PROJECTS_DIR.mkdir(parents=True, exist_ok=True)
    seen: dict[str, int] = {}
    count = 0

    for order, li in enumerate(soup.select('ul.portfolio-list li[data-type]'), start=1):
        data_type = li.get('data-type', '')
        category = CATEGORY_MAP.get(data_type)
        if not category:
            print(f'  ! skipped unknown category {data_type!r}')
            continue

        iframe = li.find('iframe')
        embed_src = iframe.get('src', '') if iframe else ''
        title = ''
        summary = ''
        media_lines: list[str] = []
        link = None

        yt = re.search(r'youtube\.com/embed/([\w-]+)', embed_src)
        sk = re.search(r'sketchfab\.com/models/([\w-]+)/embed', embed_src)

        if yt:
            video_id = yt.group(1)
            title = YT_TITLES.get(video_id, f'Video {video_id}')
            media_lines = ['media:', '  type: "youtube"', f'  videoId: {yaml_scalar(video_id)}']
            link = f'https://www.youtube.com/watch?v={video_id}'

        elif sk:
            model_id = sk.group(1)
            credit = li.select_one('p a[href*="sketchfab.com/models"]')
            title = clean(credit.get_text()) if credit else f'Model {model_id}'
            media_lines = ['media:', '  type: "sketchfab"', f'  modelId: {yaml_scalar(model_id)}']
            if credit and credit.get('href'):
                link = credit['href'].split('?')[0]

        else:
            title_el = li.select_one('a.portfolio-title')
            title = clean(title_el.get_text() if title_el else '') or f'Untitled {order}'

            summary_el = li.select_one('.portfolio-block-hover h4')
            summary = clean(summary_el.get_text() if summary_el else '')

            thumb_el = li.select_one('img')
            thumb = thumb_el.get('src') if thumb_el else None

            full = title_el.get('href') if title_el else None
            if not full or not full.startswith('images/'):
                full = thumb

            media_lines = ['media:', '  type: "image"']
            if thumb:
                media_lines.append(f'  thumbnail: {yaml_scalar(asset(thumb))}')
            if full:
                media_lines.append(f'  full: {yaml_scalar(asset(full))}')

            outer = li.select_one('.portfolio-image-block > a')
            href = outer.get('href') if outer else None
            link = href if href and href.startswith('http') else None

        slug = slugify(title)
        seen[slug] = seen.get(slug, 0) + 1
        if seen[slug] > 1:
            slug = f'{slug}-{seen[slug]}'

        lines = [
            '---',
            f'title: {yaml_scalar(title)}',
            f'category: {yaml_scalar(category)}',
            f'summary: {yaml_scalar(summary)}',
            f'order: {order}',
        ]
        lines.extend(media_lines)
        if link:
            key = 'repo' if 'github.com' in link else 'live'
            lines.append('links:')
            lines.append(f'  {key}: {yaml_scalar(link)}')
        lines.append('---')
        lines.append('')
        lines.append(
            '<!-- Optional write-up. Leave this empty and the project stays a tile '
            'in the gallery; add content and it gets its own page at '
            f'/projects/{slug}/. -->'
        )
        lines.append('')

        (PROJECTS_DIR / f'{slug}.md').write_text('\n'.join(lines), encoding='utf-8')
        count += 1

    return count


# --- Timeline sections ------------------------------------------------

def extract_timeline(section_id: str):
    section = soup.find('section', id=section_id)
    entries = []
    if not section:
        return entries

    for order, li in enumerate(section.select('.timeline li'), start=1):
        heading = li.select_one('.timeline-header h3')
        subs = [clean(p.get_text()) for p in li.select('p.sub-heading')]
        body = li.select_one('p.content-p')

        entry = {
            'id': slugify(clean(heading.get_text()) if heading else f'entry-{order}'),
            'order': order,
            'title': clean(heading.get_text()) if heading else '',
            'organization': subs[0] if subs else '',
            'period': subs[1] if len(subs) > 1 else '',
            'summary': clean(body.get_text()) if body else '',
        }
        entries.append(entry)

    return entries


# --- Certifications ---------------------------------------------------

def extract_certifications():
    section = soup.find('section', id='achievement')
    entries = []
    if not section:
        return entries

    for order, block in enumerate(section.select('.achievement'), start=1):
        title = block.select_one('.achievement-top-bar h5')
        period = block.select_one('.achievement-heading h6')
        badge = block.select_one('.achievement-heading img')
        title_text = clean(title.get_text()) if title else f'Certification {order}'

        entry = {
            'id': slugify(title_text),
            'order': order,
            'title': title_text,
            'period': clean(period.get_text()) if period else '',
        }
        if badge and badge.get('src'):
            # Prefer the hyphenated duplicate; the spaced filename is awkward to import.
            src = badge['src'].replace('Aplus Logo Certified CE.png', 'Aplus-Logo-Certified-CE.png')
            entry['badge'] = asset(src, '../assets/')
        entries.append(entry)

    return entries


# --- Skills -----------------------------------------------------------

def extract_skills():
    groups = []
    for order, card in enumerate(soup.select('#skills .skill-wrapper'), start=1):
        title = card.select_one('.skill-title')
        items = [clean(p.contents[0]) for p in card.select('.skill-progress-div p') if p.contents]
        items = [i for i in items if i]
        groups.append({
            'id': slugify(clean(title.get_text()) if title else f'group-{order}'),
            'order': order,
            'label': clean(title.get_text()) if title else '',
            'items': items,
        })
    return groups


# --- Interests --------------------------------------------------------

def extract_interests():
    # The source carries two interest lists: an empty template row inside the
    # card wrapper, and the real one. Skip anything without a label.
    entries = []
    order = 0
    for li in soup.select('li.interest-topic'):
        label = clean(li.get_text())
        if not label:
            continue
        order += 1
        icon_el = li.find('i')
        classes = icon_el.get('class', []) if icon_el else []
        icon = next((ICON_MAP[c] for c in classes if c in ICON_MAP), 'fa6-solid:star')
        entries.append({
            'id': slugify(label),
            'order': order,
            'label': label,
            'icon': icon,
        })
    return entries


# --- About ------------------------------------------------------------

def extract_about():
    PAGES_DIR.mkdir(parents=True, exist_ok=True)
    section = soup.find('section', id='about')
    prose = ''
    if section:
        paragraphs = [clean(p.get_text()) for p in section.find_all('p')]
        prose = '\n\n'.join(p for p in paragraphs if p)
    if not prose and section:
        prose = clean(section.get_text())

    body = '\n'.join([
        '---',
        'title: "Who Am I?"',
        '---',
        '',
        prose,
        '',
    ])
    (PAGES_DIR / 'about.md').write_text(body, encoding='utf-8')
    print('  src/content/pages/about.md')


# --- Run --------------------------------------------------------------

print('Projects:')
n = extract_projects()
print(f'  {n} project files written to src/content/projects/')

print('Data:')
write_json(DATA_DIR / 'experience.json', extract_timeline('experience'))
write_json(DATA_DIR / 'education.json', extract_timeline('education'))
write_json(DATA_DIR / 'certifications.json', extract_certifications())
write_json(DATA_DIR / 'skills.json', extract_skills())
write_json(DATA_DIR / 'interests.json', extract_interests())
write_json(DATA_DIR / 'categories.json', [
    {'id': slug, 'order': i, 'label': label}
    for i, (slug, label) in enumerate(CATEGORY_LABELS.items(), start=1)
])

print('Pages:')
extract_about()
print('\nDone.')
