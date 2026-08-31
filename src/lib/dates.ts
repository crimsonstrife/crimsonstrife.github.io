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

/** True once the whole expiry month has passed. */
export function hasExpired(expires?: string, now: Date = new Date()): boolean {
  if (!expires) return false;
  const [year, month] = expires.split('-').map(Number);
  if (!year || !month) return false;
  // First instant of the month after the stated expiry.
  return now >= new Date(Date.UTC(year, month, 1));
}
