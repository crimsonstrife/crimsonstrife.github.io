/**
 * GitHub data is fetched at BUILD time, not in the browser.
 *
 * The 2017 site did both of these client-side: the repo list burned the
 * visitor's 60-requests-per-hour unauthenticated quota, and the contribution
 * calendar scraped GitHub's profile HTML — which is why it stopped working.
 *
 * Every function here fails soft. A rate limit, a missing token or no network
 * returns empty data and the section quietly renders less; it never fails the
 * build.
 */

const USER = 'crimsonstrife';
const TIMEOUT_MS = 10_000;

/** Set as a repo secret and exposed to the build in deploy.yml. */
const token =
  import.meta.env.CONTRIBUTIONS_TOKEN ??
  process.env.CONTRIBUTIONS_TOKEN ??
  '';

export interface Repo {
  name: string;
  description: string | null;
  url: string;
  language: string | null;
  stars: number;
  pushedAt: string;
}

export interface ContributionDay {
  date: string;
  count: number;
  /** 0–4, matching GitHub's own intensity buckets. */
  level: number;
}

export interface Activity {
  repos: Repo[];
  /** Most common primary language across owned repos, as the 2017 card showed. */
  languages: string[];
}

export interface Contributions {
  total: number;
  weeks: ContributionDay[][];
  from: string;
  to: string;
}

async function request(url: string, init: RequestInit = {}): Promise<Response | null> {
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), TIMEOUT_MS);
  try {
    const response = await fetch(url, {
      ...init,
      signal: controller.signal,
      headers: {
        Accept: 'application/vnd.github+json',
        'User-Agent': 'patrickbarnhardt.info-build',
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
        ...init.headers,
      },
    });
    if (!response.ok) {
      console.warn(`[github] ${url} responded ${response.status}`);
      return null;
    }
    return response;
  } catch (error) {
    console.warn(`[github] ${url} failed:`, (error as Error).message);
    return null;
  } finally {
    clearTimeout(timer);
  }
}

/**
 * One request covers both the recent-repo cards and the top-languages line:
 * the full owned-repo list is fetched, the newest few are shown, and every
 * repo's primary language feeds the tally.
 */
export async function fetchActivity(limit = 6): Promise<Activity> {
  const empty: Activity = { repos: [], languages: [] };

  const response = await request(
    `https://api.github.com/users/${USER}/repos?sort=pushed&per_page=100&type=owner`
  );
  if (!response) return empty;

  try {
    const raw = (await response.json()) as any[];
    const owned = raw.filter((repo) => !repo.fork && !repo.archived);

    const tally = new Map<string, number>();
    for (const repo of owned) {
      if (!repo.language) continue;
      tally.set(repo.language, (tally.get(repo.language) ?? 0) + 1);
    }

    const languages = [...tally.entries()]
      .sort((a, b) => b[1] - a[1] || a[0].localeCompare(b[0]))
      .slice(0, 3)
      .map(([name]) => name);

    const repos = owned.slice(0, limit).map((repo) => ({
      name: repo.name,
      description: repo.description ?? null,
      url: repo.html_url,
      language: repo.language ?? null,
      stars: repo.stargazers_count ?? 0,
      pushedAt: repo.pushed_at,
    }));

    return { repos, languages };
  } catch (error) {
    console.warn('[github] could not parse repos:', (error as Error).message);
    return empty;
  }
}

const CONTRIBUTIONS_QUERY = `
  query($login: String!) {
    user(login: $login) {
      contributionsCollection {
        contributionCalendar {
          totalContributions
          weeks {
            contributionDays { date contributionCount }
          }
        }
      }
    }
  }
`;

/**
 * The contribution graph is only available through the GraphQL API, which
 * requires authentication. Without a token this returns null and the calendar
 * is simply omitted.
 */
export async function fetchContributions(): Promise<Contributions | null> {
  if (!token) {
    console.warn('[github] no CONTRIBUTIONS_TOKEN — skipping contribution calendar');
    return null;
  }

  const response = await request('https://api.github.com/graphql', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ query: CONTRIBUTIONS_QUERY, variables: { login: USER } }),
  });
  if (!response) return null;

  try {
    const payload = (await response.json()) as any;
    if (payload.errors?.length) {
      console.warn('[github] GraphQL errors:', payload.errors[0]?.message);
      return null;
    }

    const calendar = payload.data?.user?.contributionsCollection?.contributionCalendar;
    if (!calendar) return null;

    const rawWeeks: { contributionDays: { date: string; contributionCount: number }[] }[] =
      calendar.weeks ?? [];

    const counts = rawWeeks.flatMap((week) =>
      week.contributionDays.map((day) => day.contributionCount)
    );
    const max = Math.max(1, ...counts);

    const weeks = rawWeeks.map((week) =>
      week.contributionDays.map((day) => ({
        date: day.date,
        count: day.contributionCount,
        level: bucket(day.contributionCount, max),
      }))
    );

    const flat = weeks.flat();
    return {
      total: calendar.totalContributions ?? 0,
      weeks,
      from: flat[0]?.date ?? '',
      to: flat[flat.length - 1]?.date ?? '',
    };
  } catch (error) {
    console.warn('[github] could not parse contributions:', (error as Error).message);
    return null;
  }
}

/** Four intensity steps above zero, scaled to the busiest day of the year. */
function bucket(count: number, max: number): number {
  if (count <= 0) return 0;
  const ratio = count / max;
  if (ratio <= 0.25) return 1;
  if (ratio <= 0.5) return 2;
  if (ratio <= 0.75) return 3;
  return 4;
}
