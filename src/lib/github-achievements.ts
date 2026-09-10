import { parseHTML } from 'linkedom';

const PROFILE = 'https://github.com/crimsonstrife';

export interface Achievement {
  slug: string;
  name: string;
  tier: number;
  image: string;
  url: string;
}

/**
 * Public HTML extraction inspired by wangrunlin/github-achievements-api.
 * This is not an official GitHub API and may need updating when markup changes.
 * Only plain text and validated GitHub asset URLs leave the parser.
 */
export function parseAchievements(html: string): Achievement[] {
  const { document } = parseHTML(html);
  const badges = new Map<string, Achievement>();
  for (const card of document.querySelectorAll('.js-achievement-card-details')) {
    const slug = card.getAttribute('data-achievement-slug');
    const img = card.querySelector('.achievement-badge-card');
    const src = img?.getAttribute('src');
    if (!slug || !/^[a-z0-9-]+$/.test(slug) || !src) continue;
    try {
      const image = new URL(src);
      if (image.origin !== 'https://github.githubassets.com' ||
          !image.pathname.startsWith('/assets/') || image.username || image.password) continue;
      const tierText = card.querySelector('.achievement-tier-label')?.textContent?.trim() ?? '';
      const tier = /^[x×]\d+$/.test(tierText) ? Number(tierText.slice(1)) : 1;
      const name = img?.getAttribute('alt')?.replace(/^Achievement:\s*/i, '').trim();
      badges.set(slug, {
        slug,
        name: name || slug.split('-').map((word) => word[0].toUpperCase() + word.slice(1)).join(' '),
        tier: Number.isSafeInteger(tier) && tier > 0 ? tier : 1,
        image: image.href,
        url: `${PROFILE}?tab=achievements&achievement=${slug}`,
      });
    } catch {
      // An invalid badge must not prevent other valid badges from rendering.
    }
  }
  return [...badges.values()];
}

/** Build-time only; deliberately separate from authenticated GitHub requests. */
export async function fetchAchievements(): Promise<Achievement[]> {
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), 10_000);
  try {
    const response = await fetch(`${PROFILE}?tab=achievements`, {
      signal: controller.signal,
      headers: { Accept: 'text/html', 'User-Agent': 'patrickbarnhardt.info-build' },
    });
    if (!response.ok) {
      console.warn(`[github] achievements page responded ${response.status}`);
      return [];
    }
    const achievements = parseAchievements(await response.text());
    if (!achievements.length) console.warn('[github] no recognizable public achievement cards');
    return achievements;
  } catch {
    console.warn('[github] could not load public achievements');
    return [];
  } finally {
    clearTimeout(timer);
  }
}
