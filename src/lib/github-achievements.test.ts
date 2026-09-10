import { afterEach, expect, it, vi } from 'vitest';
import { fetchAchievements, parseAchievements } from './github-achievements';

const card = (slug = 'pull-shark', image = 'https://github.githubassets.com/assets/pull-shark.png', tier = 'x3') => `
<details class="js-achievement-card-details" data-achievement-slug="${slug}">
  <summary><img class="achievement-badge-card" src="${image}" alt="Achievement: Pull Shark" />
  <span class="achievement-tier-label">${tier}</span></summary>
</details>`;

afterEach(() => { vi.unstubAllGlobals(); vi.restoreAllMocks(); });

it('extracts names, tier labels and safe badge links, ignoring duplicate cards', () => {
  const achievements = parseAchievements(card() + card());
  expect(achievements).toEqual([{
    slug: 'pull-shark', name: 'Pull Shark', tier: 3,
    image: 'https://github.githubassets.com/assets/pull-shark.png',
    url: 'https://github.com/crimsonstrife?tab=achievements&achievement=pull-shark',
  }]);
});

it('skips malformed images, missing fields and untrusted URLs', () => {
  expect(parseAchievements(card('bad', 'javascript:alert(1)') + card('bad', 'https://example.com/image.png') +
    '<details class="js-achievement-card-details"></details>')).toEqual([]);
  expect(parseAchievements(card('pull-shark', undefined, 'unexpected'))[0].tier).toBe(1);
});

it('fetches public HTML without an authorization header', async () => {
  const fetch = vi.fn().mockResolvedValue(new Response(card()));
  vi.stubGlobal('fetch', fetch);
  expect(await fetchAchievements()).toHaveLength(1);
  const [url, init] = fetch.mock.calls[0];
  expect(url).toBe('https://github.com/crimsonstrife?tab=achievements');
  expect(new Headers(init.headers).has('Authorization')).toBe(false);
});

it('omits badges on rate limits, network errors, or changed markup', async () => {
  vi.spyOn(console, 'warn').mockImplementation(() => {});
  const fetch = vi.fn()
    .mockResolvedValueOnce(new Response('limited', { status: 429 }))
    .mockRejectedValueOnce(new Error('offline'))
    .mockResolvedValueOnce(new Response('<html>different profile structure</html>'));
  vi.stubGlobal('fetch', fetch);
  expect(await fetchAchievements()).toEqual([]);
  expect(await fetchAchievements()).toEqual([]);
  expect(await fetchAchievements()).toEqual([]);
});
