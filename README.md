# patrickbarnhardt.info

Personal portfolio and resume site for Patrick Barnhardt, built with
[Astro](https://astro.build) and deployed to GitHub Pages at
<https://www.patrickbarnhardt.info>.

## Requirements

- Node 22.12 or newer

## Getting started

```bash
npm install
npm run dev      # http://localhost:4321
npm run build    # type-check, then build to dist/
npm run preview  # serve the production build locally
```

## Adding content

Content is plain files — no CMS, no admin panel. Add a file, push, done.

| To add… | Create or edit |
| --- | --- |
| A portfolio project | `src/content/projects/<slug>.md` |
| A blog post | `src/content/blog/<slug>.md` |
| A job | an entry in `src/data/experience.json` |
| A degree | an entry in `src/data/education.json` |
| A certification | an entry in `src/data/certifications.json` |
| A social profile | an entry in `src/data/social.json` |
| A skill | an entry in `src/data/skills.json` |

Every collection is validated by a Zod schema in `src/content.config.ts`.
A missing field or a mistyped category fails the build rather than
shipping a broken page.

Astro writes JSON Schema files to `.astro/collections/` on each build.
Point your editor at them for autocomplete while editing the `.json`
files above.

## Project layout

```
public/          Served as-is (CNAME, favicon)
src/assets/      Images and fonts processed at build time
src/components/  Section and UI components
src/content/     Markdown collections (projects, blog)
src/data/        JSON collections (resume, social, skills)
src/layouts/     Page shells
src/pages/       Routes
src/styles/      tokens.css defines the whole visual system
```

## GitHub activity

The repository list and the contribution calendar in the Work section are
fetched at **build time**, not in the browser — no rate limit spent on
visitors, no loading spinner, nothing to break when GitHub changes its
markup.

The contribution graph is only available through GitHub's GraphQL API,
which requires authentication:

1. Create a fine-grained personal access token with read access to your
   profile's contribution data.
2. Add it to the repository under **Settings → Secrets and variables →
   Actions** as `GITHUB_CONTRIBUTIONS_TOKEN`.

Without the token the calendar is skipped and the build still succeeds —
see `src/lib/github.ts`, where every network call fails soft.

A nightly workflow run at 07:00 UTC rebuilds the site so this data stays
current between pushes.

To see the real data locally:

```bash
GITHUB_CONTRIBUTIONS_TOKEN=github_pat_... npm run build
```

## Deployment

Pushing to `master` triggers `.github/workflows/deploy.yml`, which builds
the site and publishes it to GitHub Pages. Pushes to `astro-replacement`
build but do **not** deploy, so the live site is never touched before the
merge.

### Before the first deploy

- Set **Settings → Pages → Source** to **GitHub Actions** (it is currently
  set to deploy from a branch).
- Add the `GITHUB_CONTRIBUTIONS_TOKEN` secret described above.
- Confirm the custom domain still resolves — `public/CNAME` carries
  `www.patrickbarnhardt.info`.

## License

See [LICENSE.MD](LICENSE.MD).
