/**
 * The extractor leaves an HTML comment in every project file explaining that
 * a write-up is optional, so those comments never count as content.
 */
function stripComments(body: string): string {
  return body.replace(/<!--[\s\S]*?-->/g, '');
}

/**
 * A project's Markdown body is optional. "Has a write-up" means "has something
 * once the comments and whitespace are gone".
 */
export function hasWriteup(body?: string): boolean {
  return Boolean(body && stripComments(body).trim());
}

/** Drafts are visible in `astro dev` but never in a production build. */
export const includeDrafts = import.meta.env.DEV;

const WORDS_PER_MINUTE = 200;

/**
 * Minutes to read a write-up, from its raw Markdown body.
 *
 * The body arrives with frontmatter already stripped, but Markdown syntax and
 * link URLs are still in the string, so the count runs a few percent high.
 * That is well inside the noise of any reading estimate and not worth a second
 * pass to correct. Rounds up, because a "2 min read" that takes four is a
 * worse lie than one that takes ninety seconds.
 */
export function readingTime(body?: string): number {
  const words = stripComments(body ?? '').trim().split(/\s+/).filter(Boolean).length;
  return Math.max(1, Math.ceil(words / WORDS_PER_MINUTE));
}
