import { afterEach, expect, it, vi } from 'vitest';

const publicRepo = {
  name: 'public-project', private: false, fork: false, archived: false,
  html_url: 'https://github.com/crimsonstrife/public-project',
  language: 'TypeScript', pushed_at: '2026-09-01T00:00:00Z',
};
const privateRepo = {
  name: 'One-Mans-Poison', full_name: 'crimsonstrife/One-Mans-Poison',
  private: true, fork: false, archived: false, language: 'C++',
  description: 'PRIVATE DESCRIPTION MUST NOT LEAK',
  html_url: 'https://github.com/crimsonstrife/One-Mans-Poison',
  pushed_at: '2026-09-10T00:00:00Z', stargazers_count: 42,
};

async function setup(token: string, privateStatus = 200, publicStatus = 200) {
  vi.resetModules();
  vi.stubEnv('CONTRIBUTIONS_TOKEN', token);
  vi.spyOn(console, 'warn').mockImplementation(() => {});
  const fetch = vi.fn(async (url: string) => {
    if (url.includes('/users/crimsonstrife/repos?')) {
      // Defense in depth: an unexpected private item must be discarded.
      return Response.json([publicRepo, { ...privateRepo, name: 'unlisted-secret' }], { status: publicStatus });
    }
    expect(url).toBe('https://api.github.com/repos/crimsonstrife/One-Mans-Poison');
    return Response.json(privateRepo, { status: privateStatus });
  });
  vi.stubGlobal('fetch', fetch);
  const { fetchActivity } = await import('./github');
  return { fetchActivity, fetch };
}

afterEach(() => {
  vi.unstubAllEnvs();
  vi.unstubAllGlobals();
  vi.restoreAllMocks();
});

it('ranks the selected private project by push time and publishes only approved metadata', async () => {
  const { fetchActivity, fetch } = await setup('test-token');
  const activity = await fetchActivity(1);
  expect(activity.repos).toEqual([{
    name: "One Man's Poison",
    description: 'A solo-developed survival-horror game in Unreal Engine 5.',
    url: null, isPrivate: true, language: 'C++', stars: 0,
    pushedAt: '2026-09-10T00:00:00Z',
  }]);
  expect(activity.languages).toEqual(['TypeScript']);
  expect(fetch).toHaveBeenCalledTimes(2);
  expect(JSON.stringify(activity)).not.toMatch(/PRIVATE DESCRIPTION|unlisted-secret|test-token|github.com\/crimsonstrife\/One-Mans-Poison/);
});

it('keeps public activity when private access is denied', async () => {
  const { fetchActivity } = await setup('test-token', 404);
  expect((await fetchActivity()).repos.map((repo) => repo.name)).toEqual(['public-project']);
});

it('does not request private metadata without a token', async () => {
  const { fetchActivity, fetch } = await setup('');
  expect((await fetchActivity()).repos.map((repo) => repo.name)).toEqual(['public-project']);
  expect(fetch).toHaveBeenCalledTimes(1);
});

it('keeps selected private activity when the public request fails', async () => {
  const { fetchActivity } = await setup('test-token', 200, 503);
  const activity = await fetchActivity();
  expect(activity.repos.map((repo) => repo.name)).toEqual(["One Man's Poison"]);
  expect(activity.languages).toEqual([]);
});
