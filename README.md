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
   Actions** as `CONTRIBUTIONS_TOKEN`.

Without the token the calendar is skipped and the build still succeeds —
see `src/lib/github.ts`, where every network call fails soft.

A nightly workflow run at 07:00 UTC rebuilds the site so this data stays
current between pushes.

To see the real data locally:

```bash
CONTRIBUTIONS_TOKEN=github_pat_... npm run build
```

## Contact form

The site remains static on GitHub Pages, while contact submissions post to a
small Cloudflare Worker at `contact.patrickbarnhardt.info`. The Worker validates
the request, checks its Turnstile token, and uses Cloudflare Email Service to
deliver it to `contact@patrickbarnhardt.info`.

The two homepage entry points share the endpoint but send different fields and
inbox subjects for project enquiries and game-development conversations.

For a local end-to-end form test, run the site and Worker in separate terminals:

```bash
cp workers/contact/.dev.vars.example workers/contact/.dev.vars
npm run dev
npm run contact:dev
```

Local builds use Cloudflare's public dummy Turnstile keys. The production widget
is restricted to `patrickbarnhardt.info` (which also authorizes `www`), and the
production Worker accepts those two origins and hostnames only, together with
the `portfolio-contact` action.

## Deployment

Pushing to `master` triggers `.github/workflows/deploy.yml`, which builds the
site, validates the contact Worker, publishes the site to GitHub Pages, and
deploys the Worker to Cloudflare. Pushes to `astro-replacement` and pull
requests run the checks but do **not** deploy. Nightly builds refresh the site
without creating a new Worker version.

### Before the first deploy

- Set **Settings → Pages → Source** to **GitHub Actions** (it is currently
  set to deploy from a branch).
- Add the `CONTRIBUTIONS_TOKEN` secret described above.
- Add `CLOUDFLARE_API_TOKEN` and `CLOUDFLARE_ACCOUNT_ID` as GitHub Actions
  secrets for Worker deployments.
- Confirm the custom domain still resolves — `public/CNAME` carries
  `www.patrickbarnhardt.info`.

## License

Source code originally authored for this site is available under the
[MIT License](LICENSE.MD). Portfolio copy, media, personal likeness, trademarks,
trade names, logos and other brand assets are not included in that software
license. Third-party materials retain their respective rights and licenses.
