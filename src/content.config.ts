import { defineCollection } from 'astro:content';
import { file, glob } from 'astro/loaders';
import { z } from 'astro/zod';

/**
 * Every repeatable list on the site is a collection with a Zod schema, so a
 * missing field or a mistyped category fails the build instead of shipping a
 * broken section.
 *
 * Astro writes JSON Schema files to `.astro/collections/` on each build —
 * point your editor's `json.schemas` setting at those for autocomplete while
 * editing the JSON files in src/data/.
 *
 * Ordering note: getCollection() returns file()-loaded entries sorted by id,
 * which discards the order of the JSON array. Anything order-sensitive carries
 * an explicit `order` key and is sorted at the call site.
 */

const CATEGORIES = [
  'games',
  '3d',
  'branding',
  'web',
  'textures',
  'blueprints',
  'tools',
  'uxui',
  'cad',
  'video',
] as const;

/**
 * The context a project was made in — a different question from the discipline
 * `category` records, so the two are separate axes rather than one flattened
 * list. Mods are deliberately their own value rather than folded into game or
 * art work: they ship into someone else's game and community, and that is what
 * distinguishes them, not their craft.
 */
const TRACKS = [
  'professional',
  'independent',
  'mods',
  'education',
  'content',
] as const;

const links = z
  .object({
    repo: z.url().optional(),
    live: z.url().optional(),
    /** Project documentation, when the docs are separate from the live application. */
    docs: z.url().optional(),
    /** A release or download page, for things that are installed rather than visited. */
    download: z.url().optional(),
  })
  .default({});

/**
 * The gallery holds three kinds of item. A tile renders from `media.type`:
 * an optimized image, a YouTube embed, or a Sketchfab 3D viewer.
 */
const projects = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/projects' }),
  schema: ({ image }) =>
    z.object({
      title: z.string(),
      category: z.enum(CATEGORIES),
      summary: z.string().default(''),
      /**
       * The year the work shipped, or was last substantially worked on. This
       * is the archive's primary sort key and what its era grouping reads.
       */
      year: z.number().int().min(1990).max(2100),
      /**
       * True when `year` is a best estimate rather than a sourced date, and
       * renders as "c. 2015". Much of the pre-2017 work predates any record
       * that survives; saying so is better than implying a precision we don't
       * have. Evidence for every date is in scripts/project-dating.json.
       */
      yearApprox: z.boolean().default(false),
      /** The context the work was made in. See TRACKS above. */
      track: z.enum(TRACKS),
      /**
       * Optional pin, ascending, applied only within a year. Most projects
       * don't need one — `year` and then title order is enough. Set it to
       * force a specific project to the head of its year.
       */
      order: z.number().int().nonnegative().optional(),
      /**
       * Marks the project for the full-width feature panel above the gallery —
       * the 2017 site's "showbox". Only the first featured project renders.
       */
      featured: z.boolean().default(false),
      /** Selling points shown beside a featured project. */
      featureHighlights: z
        .array(
          z.object({
            icon: z.string(),
            title: z.string(),
            body: z.string(),
          })
        )
        .default([]),
      /**
       * Marks a long-form write-up. Drives the "Case study" label, a reading
       * estimate, and — once there are enough headings — a table of contents.
       * Everything else about the entry is unchanged; this only affects how
       * the detail page is presented.
       */
      caseStudy: z.boolean().default(false),
      tags: z.array(z.string()).default([]),
      /**
       * Attribution for artwork that isn't Patrick's. Rendered wherever the
       * image is shown, so a commissioned piece can headline a project without
       * implying he made it.
       */
      imageCredit: z
        .object({
          name: z.string(),
          role: z.string().optional(),
          url: z.url().optional(),
        })
        .optional(),
      links,
      /**
       * Optional. A project with nothing to show yet still earns a tile — it
       * renders as a titled panel and, if it has a write-up, links to its page.
       */
      media: z
        .discriminatedUnion('type', [
        z.object({
          type: z.literal('image'),
          thumbnail: image(),
          full: image().optional(),
        }),
        z.object({
          type: z.literal('youtube'),
          videoId: z.string(),
        }),
        z.object({
          type: z.literal('sketchfab'),
          modelId: z.string(),
          /**
           * A still for the gallery tile. Sketchfab has no derivable thumbnail
           * URL, so without one the tile falls back to a titled panel.
           */
          poster: image().optional(),
        }),
        ])
        .optional(),
      /**
       * Supporting images shown after the write-up, opening in the shared
       * lightbox. Paths are relative to this Markdown file, like `media`.
       */
      gallery: z
        .object({
          title: z.string().optional(),
          items: z
            .array(
              z.object({
                image: image(),
                alt: z.string(),
                caption: z.string().optional(),
              })
            )
            .min(1),
        })
        .optional(),
    }),
});

const blog = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/blog' }),
  schema: ({ image }) =>
    z.object({
      title: z.string(),
      description: z.string(),
      pubDate: z.coerce.date(),
      updatedDate: z.coerce.date().optional(),
      tags: z.array(z.string()).default([]),
      /** Drafts are excluded from production builds but visible in `astro dev`. */
      draft: z.boolean().default(false),
      heroImage: image().optional(),
    }),
});

const pages = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/pages' }),
  schema: z.object({
    title: z.string(),
    /**
     * A resumé-length version of the page body. about.md renders in full in the
     * homepage About section, where four paragraphs are right; the printable
     * resumé uses this instead, where they are not. Omitted, /resume falls back
     * to the full body.
     */
    summary: z.string().optional(),
  }),
});

/** Shared shape for the two resumé timelines. */
/**
 * A bullet point. A bare string appears everywhere. The object form exists for
 * lines that belong on the site but not on paper — an honest aside reads as
 * candour on a page someone chose to visit, and as an argument against yourself
 * in a stack of forty resumés.
 */
const highlight = z.union([
  z.string(),
  z.object({
    text: z.string(),
    print: z.boolean().default(true),
  }),
]);

const timelineEntry = z.object({
  id: z.string(),
  order: z.number().int().positive(),
  title: z.string(),
  organization: z.string(),
  period: z.string(),
  /**
   * Machine-readable bounds for the same span `period` describes in prose.
   * `end` omitted means the role is current. These drive the printable
   * resumé's history window and the structured data — `period` stays the
   * only thing rendered, so the two can never disagree on screen.
   */
  start: z.string().regex(/^\d{4}(-\d{2})?$/, 'Expected YYYY or YYYY-MM'),
  end: z.string().regex(/^\d{4}(-\d{2})?$/, 'Expected YYYY or YYYY-MM').optional(),
  summary: z.string().default(''),
  /** Bullet points, where the role has them. */
  highlights: z.array(highlight).default([]),
  credentialId: z.string().optional(),
  credentialUrl: z.url().optional(),
  /**
   * Technical/professional roles. The site lists every entry (the most recent
   * few up front, the rest behind a toggle); the printable resumé at /resume
   * shows only the entries flagged here.
   */
  technical: z.boolean().default(false),
  /**
   * Editorial suppression for the printed resumé, and deliberately a separate
   * question from `technical`: that one asks what a role *is*, this one asks
   * whether you want it on paper. Applied after the history window is worked
   * out, so hiding an entry removes it rather than pulling an older one in to
   * take its place.
   */
  printable: z.boolean().default(true),
  /**
   * Replaces `period` on the printed resumé only. Lets one entry cover the span
   * that two entries describe on the site — a promotion inside the same company
   * is worth two rows on a timeline and one row on a resumé.
   */
  printPeriod: z.string().optional(),
});

const experience = defineCollection({
  loader: file('src/data/experience.json'),
  schema: timelineEntry,
});

const education = defineCollection({
  loader: file('src/data/education.json'),
  schema: timelineEntry,
});

const certifications = defineCollection({
  loader: file('src/data/certifications.json'),
  schema: ({ image }) =>
    z.object({
      id: z.string(),
      order: z.number().int().positive(),
      title: z.string(),
      issuer: z.string().optional(),
      issued: z.string().regex(/^\d{4}-\d{2}$/, 'Expected a YYYY-MM month'),
      /**
       * Lapsed credentials stay on the site — the training still counts — and
       * are labelled as expired rather than removed.
       */
      expires: z.string().regex(/^\d{4}-\d{2}$/, 'Expected a YYYY-MM month').optional(),
      credentialId: z.string().optional(),
      credentialUrl: z.url().optional(),
      badge: image().optional(),
    }),
});

/** Recognitions rather than credentials: community awards, cadet honours. */
const awards = defineCollection({
  loader: file('src/data/awards.json'),
  schema: ({ image }) =>
    z.object({
      id: z.string(),
      order: z.number().int().positive(),
      title: z.string(),
      issuer: z.string(),
      issued: z.string().regex(/^\d{4}-\d{2}$/, 'Expected a YYYY-MM month').optional(),
      credentialUrl: z.url().optional(),
      badge: image().optional(),
    }),
});

const skills = defineCollection({
  loader: file('src/data/skills.json'),
  schema: z.object({
    id: z.string(),
    order: z.number().int().positive(),
    label: z.string(),
    items: z.array(z.string()).min(1),
    /**
     * The subset to print at /resume. A printed resumé is read in about six
     * seconds, so it wants the relevant dozen rather than the honest seventy —
     * but the site keeps the full list, because the full list is true. Omit
     * this and the group prints every item in `items`.
     */
    printItems: z.array(z.string()).optional(),
  }),
});

const interests = defineCollection({
  loader: file('src/data/interests.json'),
  schema: z.object({
    id: z.string(),
    order: z.number().int().positive(),
    label: z.string(),
    icon: z.string().regex(/^[a-z0-9-]+:[a-z0-9-]+$/, 'Expected an Iconify name'),
  }),
});

const categories = defineCollection({
  loader: file('src/data/categories.json'),
  schema: z.object({
    id: z.enum(CATEGORIES),
    order: z.number().int().positive(),
    label: z.string(),
  }),
});

const tracks = defineCollection({
  loader: file('src/data/tracks.json'),
  schema: z.object({
    id: z.enum(TRACKS),
    order: z.number().int().positive(),
    label: z.string(),
    blurb: z.string(),
  }),
});

const social = defineCollection({
  loader: file('src/data/social.json'),
  schema: z.object({
    id: z.string(),
    order: z.number().int().positive(),
    label: z.string(),
    url: z.url(),
    /** Full profile URL used by structured data when the visible link is a short URL. */
    canonicalUrl: z.url().optional(),
    icon: z
      .string()
      .regex(
        /^(?:[a-z0-9-]+:[a-z0-9-]+|\/[a-z0-9/_-]+\.png)$/,
        'Expected an Iconify name like "fa6-brands:github" or a root-relative PNG path'
      ),
    color: z.string().regex(/^#[0-9a-fA-F]{6}$/, 'Expected a 6-digit hex color'),
    description: z.string(),
  }),
});

export const collections = {
  projects,
  blog,
  pages,
  experience,
  education,
  certifications,
  awards,
  skills,
  interests,
  categories,
  tracks,
  social,
};
