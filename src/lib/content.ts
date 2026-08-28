/**
 * A project's Markdown body is optional. The extractor leaves an HTML comment
 * in every file explaining that, so "has a write-up" means "has something once
 * the comments and whitespace are gone".
 */
export function hasWriteup(body?: string): boolean {
  return Boolean(body?.replace(/<!--[\s\S]*?-->/g, '').trim());
}

/** Drafts are visible in `astro dev` but never in a production build. */
export const includeDrafts = import.meta.env.DEV;
