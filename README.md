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

## Deployment

Pushing to `master` triggers `.github/workflows/deploy.yml`, which builds
the site and publishes it to GitHub Pages. Pushes to other branches build
but do not deploy.

The repository's **Settings → Pages → Source** must be set to
**GitHub Actions**.

## License

See [LICENSE.MD](LICENSE.MD).
