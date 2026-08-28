import { defineCollection } from 'astro:content';
import { file } from 'astro/loaders';
import { z } from 'astro/zod';

/**
 * Collections are added a phase at a time. Each one is validated by a Zod
 * schema, so a missing field or a mistyped value fails the build instead of
 * shipping a broken section.
 *
 * Astro writes JSON Schema files to `.astro/collections/` on every build —
 * point your editor at those for autocomplete while editing the JSON.
 */

const social = defineCollection({
  loader: file('src/data/social.json'),
  schema: z.object({
    id: z.string(),
    /** Display order. getCollection() returns file() entries sorted by id, so
     *  anything order-sensitive needs an explicit key to sort on. */
    order: z.number().int().positive(),
    label: z.string(),
    url: z.url(),
    /** Iconify name, e.g. "fa6-brands:twitch". */
    icon: z.string().regex(/^[a-z0-9-]+:[a-z0-9-]+$/, 'Expected an Iconify name like "fa6-brands:github"'),
    /** Brand colour used for the icon tile. */
    color: z.string().regex(/^#[0-9a-fA-F]{6}$/, 'Expected a 6-digit hex colour'),
    description: z.string(),
  }),
});

export const collections = { social };
