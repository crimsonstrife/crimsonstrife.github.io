import { parseBound, yearsSince } from './dates';

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

interface DatedEntry {
  data: { order: number; start: string; technical: boolean };
}

/**
 * Picks the roles that belong on the printable resumé.
 *
 * Two rules, whichever reaches further back: always show at least
 * `minEntries` roles, and keep going until the printed history spans at
 * least `minYears`. Conventional advice is 10–15 years — see
 * https://www.monster.com/career-advice/resume/how-far-back-should-resume-go —
 * and both numbers live in src/data/site.json so the window can be retuned
 * without touching this file.
 *
 * Entries are assumed pre-filtered to the ones eligible for the resumé and
 * are sorted most-recent-first here by their `order` key. If the history is
 * shorter than `minYears`, every eligible entry is returned rather than
 * padding with roles that do not exist.
 */
export function resumeHistory<T extends DatedEntry>(
  entries: T[],
  { minEntries, minYears }: { minEntries: number; minYears: number },
  now: Date = new Date()
): T[] {
  const ordered = [...entries].sort((a, b) => a.data.order - b.data.order);
  const picked: T[] = [];

  for (const entry of ordered) {
    // Stop once both rules are satisfied — never mid-role.
    if (picked.length >= minEntries && coversMinimum(picked, minYears, now)) break;
    picked.push(entry);
  }

  return picked;
}

function coversMinimum(picked: { data: { start: string } }[], minYears: number, now: Date): boolean {
  const oldest = picked
    .map((entry) => parseBound(entry.data.start))
    .filter((date): date is Date => date !== null)
    .sort((a, b) => a.valueOf() - b.valueOf())[0];
  return oldest ? yearsSince(oldest, now) >= minYears : false;
}
