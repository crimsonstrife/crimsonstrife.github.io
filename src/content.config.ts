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

const links = z
  .object({
    repo: z.url().optional(),
    live: z.url().optional(),
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
      order: z.number().int().positive(),
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
      tags: z.array(z.string()).default([]),
      links,
      media: z.discriminatedUnion('type', [
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
        }),
      ]),
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
  }),
});

/** Shared shape for the two résumé timelines. */
const timelineEntry = z.object({
  id: z.string(),
  order: z.number().int().positive(),
  title: z.string(),
  organization: z.string(),
  period: z.string(),
  summary: z.string().default(''),
  /** Bullet points, where the role has them. */
  highlights: z.array(z.string()).default([]),
  credentialId: z.string().optional(),
  credentialUrl: z.url().optional(),
  /**
   * Technical/professional roles. The site lists every entry (the most recent
   * few up front, the rest behind a toggle); the printable résumé at /resume
   * shows only the entries flagged here.
   */
  technical: z.boolean().default(false),
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
  schema: z.object({
    id: z.string(),
    order: z.number().int().positive(),
    title: z.string(),
    issuer: z.string(),
    issued: z.string().regex(/^\d{4}-\d{2}$/, 'Expected a YYYY-MM month').optional(),
    credentialUrl: z.url().optional(),
  }),
});

const skills = defineCollection({
  loader: file('src/data/skills.json'),
  schema: z.object({
    id: z.string(),
    order: z.number().int().positive(),
    label: z.string(),
    items: z.array(z.string()).min(1),
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

const social = defineCollection({
  loader: file('src/data/social.json'),
  schema: z.object({
    id: z.string(),
    order: z.number().int().positive(),
    label: z.string(),
    url: z.url(),
    icon: z.string().regex(/^[a-z0-9-]+:[a-z0-9-]+$/, 'Expected an Iconify name like "fa6-brands:github"'),
    color: z.string().regex(/^#[0-9a-fA-F]{6}$/, 'Expected a 6-digit hex colour'),
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
  social,
};
