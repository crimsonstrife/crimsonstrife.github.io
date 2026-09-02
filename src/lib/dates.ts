/** Credential dates are stored as `YYYY-MM` so they sort and format reliably. */

export function formatMonth(value?: string): string {
  if (!value) return '';
  const date = new Date(`${value}-01T00:00:00Z`);
  if (Number.isNaN(date.valueOf())) return value;
  return date.toLocaleDateString('en-US', {
    month: 'long',
    year: 'numeric',
    timeZone: 'UTC',
  });
}

/** Year alone. The printed resumé lists credentials by year — the month adds
 *  a line-wrap per entry and nothing a reader of a resumé is looking for. */
export function formatYearOnly(value?: string): string {
  return value ? value.slice(0, 4) : '';
}

/** True once the whole expiry month has passed. */
export function hasExpired(expires?: string, now: Date = new Date()): boolean {
  if (!expires) return false;
  const [year, month] = expires.split('-').map(Number);
  if (!year || !month) return false;
  // First instant of the month after the stated expiry.
  return now >= new Date(Date.UTC(year, month, 1));
}

/** Parses a `YYYY` or `YYYY-MM` bound to the first instant of that period. */
export function parseBound(value?: string): Date | null {
  if (!value) return null;
  const [year, month] = value.split('-').map(Number);
  if (!year) return null;
  return new Date(Date.UTC(year, (month ?? 1) - 1, 1));
}

/** Whole years between `from` and `now`, ignoring partial years. */
export function yearsSince(from: Date, now: Date = new Date()): number {
  let years = now.getUTCFullYear() - from.getUTCFullYear();
  const monthDelta = now.getUTCMonth() - from.getUTCMonth();
  if (monthDelta < 0) years -= 1;
  return years;
}
