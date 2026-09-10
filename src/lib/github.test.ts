import { afterEach, expect, it, vi } from 'vitest';

const publicRepo = {
  id: 1, name: 'public-project', full_name: 'crimsonstrife/public-project',
  private: false, fork: false, archived: false,
  html_url: 'https://github.com/crimsonstrife/public-project',
  language: 'TypeScript', pushed_at: '2026-09-01T00:00:00Z',
};
const privateRepo = {
  id: 2, name: 'One-Mans-Poison', full_name: 'crimsonstrife/One-Mans-Poison',
  private: true, fork: false, archived: false, language: 'C++',
  description: 'PRIVATE DESCRIPTION MUST NOT LEAK',
  html_url: 'https://github.com/crimsonstrife/One-Mans-Poison',
  pushed_at: '2026-09-10T00:00:00Z', stargazers_count: 42,
};
const hiddenRepo = {
  ...privateRepo, id: 3, name: 'unlisted-secret', full_name: 'another-org/unlisted-secret',
  language: 'Rust', fork: true, archived: true,
};

async function setup(options: { token?: string; privateStatus?: number; publicStatus?: number; allStatus?: number; failSecondPage?: boolean } = {}) {
  vi.resetModules();
  vi.stubEnv('CONTRIBUTIONS_TOKEN', options.token ?? 'test-token');
  vi.spyOn(console, 'warn').mockImplementation(() => {});
  const fetch = vi.fn(async (url: string) => {
    const parsed = new URL(url);
    if (parsed.pathname === '/users/crimsonstrife/repos') {
      // Unexpected private items must never become cards.
      return Response.json([publicRepo, hiddenRepo], { status: options.publicStatus ?? 200 });
    }
    if (parsed.pathname === '/user/repos') {
      if (parsed.searchParams.get('page') === '1') {
        return Response.json([publicRepo, privateRepo], {
          status: options.allStatus ?? 200,
          headers: { Link: '<https://api.github.com/user/repos?page=2>; rel="next"' },
        });
      }
      // Duplicate public item exercises de-duplication across pages.
      return Response.json([publicRepo, hiddenRepo], { status: options.failSecondPage ? 503 : 200 });
    }
    if (parsed.pathname === '/repos/crimsonstrife/One-Mans-Poison') {
      return Response.json(privateRepo, { status: options.privateStatus ?? 200 });
    }
    if (parsed.pathname.startsWith('/repos/')) return Response.json({}, { status: 404 });
    throw new Error('Unexpected request');
  });
  vi.stubGlobal('fetch', fetch);
  const api = await import('./github');
  return { ...api, fetch };
}

afterEach(() => {
  vi.unstubAllEnvs();
  vi.unstubAllGlobals();
  vi.restoreAllMocks();
});

it('tallies all accessible pages, including hidden, archived and forked repos, without exposing them', async () => {
  const { fetchActivity, fetch } = await setup();
  const activity = await fetchActivity(9);
  expect(activity.repos.map((repo) => repo.name)).toEqual(["One Man's Poison", 'public-project']);
  expect(activity.repos[0]).toMatchObject({ url: null, isPrivate: true, language: 'C++', stars: 0 });
  expect(activity.repos[0].description).toContain('solo-developed survival-horror game');
  expect(activity.languages).toEqual(['C++', 'Rust', 'TypeScript']);
  expect(fetch.mock.calls.some(([url]) => url.includes('/user/repos?') && url.includes('page=2'))).toBe(true);
  expect(JSON.stringify(activity)).not.toMatch(/PRIVATE DESCRIPTION|unlisted-secret|another-org|test-token|github.com\/crimsonstrife\/One-Mans-Poison/);
});

it('applies the card limit after merging and sorting without limiting language statistics', async () => {
  const { fetchActivity } = await setup();
  const activity = await fetchActivity(1);
  expect(activity.repos.map((repo) => repo.name)).toEqual(["One Man's Poison"]);
  expect(activity.languages).toHaveLength(3);
});

it('keeps public activity when selected private access is denied', async () => {
  const { fetchActivity } = await setup({ privateStatus: 404 });
  expect((await fetchActivity()).repos.map((repo) => repo.name)).toEqual(['public-project']);
});

it('does not request private or authenticated metadata without a token', async () => {
  const { fetchActivity, fetch } = await setup({ token: '' });
  expect((await fetchActivity()).repos.map((repo) => repo.name)).toEqual(['public-project']);
  expect(fetch).toHaveBeenCalledTimes(1);
});

it('keeps private activity and all-repo languages when the public request fails', async () => {
  const { fetchActivity } = await setup({ publicStatus: 503 });
  const activity = await fetchActivity();
  expect(activity.repos.map((repo) => repo.name)).toEqual(["One Man's Poison"]);
  expect(activity.languages).toEqual(['C++', 'Rust', 'TypeScript']);
});

it('falls back to public statistics if an authenticated page fails', async () => {
  const { fetchActivity } = await setup({ failSecondPage: true });
  const activity = await fetchActivity();
  expect(activity.languages).toEqual(['TypeScript']);
  expect(activity.repos).toHaveLength(2);
});

it('renders only public active sponsorship recipients and paginates', async () => {
  const { fetchSponsorships, fetch } = await setup();
  fetch.mockImplementation(async (_url, ...args: unknown[]) => {
    const init = args[0] as RequestInit;
    const body = JSON.parse(init.body as string);
    expect(body.query).toContain('activeOnly: true');
    expect(body.query).not.toContain('tier {');
    const second = body.variables.after === 'cursor-1';
    return Response.json({ data: { user: { sponsorshipsAsSponsor: {
      pageInfo: { hasNextPage: !second, endCursor: second ? null : 'cursor-1' },
      nodes: second ? [{ privacyLevel: 'PUBLIC', sponsorable: { login: 'second', name: null } }] : [
        { privacyLevel: 'PUBLIC', sponsorable: { login: 'YarnSpinnerTool', name: 'Yarn Spinner' } },
        { privacyLevel: 'PRIVATE', sponsorable: { login: 'secret-sponsor', name: 'Secret' } },
      ],
    } } } });
  });
  const sponsors = await fetchSponsorships();
  expect(sponsors.map((sponsor) => sponsor.login)).toEqual(['second', 'YarnSpinnerTool']);
  expect(JSON.stringify(sponsors)).not.toContain('secret-sponsor');
});

it('omits sponsorships on GraphQL failure', async () => {
  const { fetchSponsorships, fetch } = await setup();
  fetch.mockResolvedValue(Response.json({ errors: [{ message: 'Permission denied' }] }));
  expect(await fetchSponsorships()).toEqual([]);
});

it('omits sponsorships without a token', async () => {
  const { fetchSponsorships, fetch } = await setup({ token: '' });
  expect(await fetchSponsorships()).toEqual([]);
  expect(fetch).not.toHaveBeenCalled();
});
