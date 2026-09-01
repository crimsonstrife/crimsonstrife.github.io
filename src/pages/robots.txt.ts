import type { APIRoute } from 'astro';

/**
 * Generated rather than dropped in public/ so the sitemap URL always tracks
 * `site` in astro.config.mjs instead of drifting when the domain changes.
 *
 * On what the AI-crawler entries are actually worth: with no robots.txt at
 * all, everything is allowed by default, so these lines grant no access that
 * did not already exist. They are here to make the policy explicit and
 * reviewable, and because the crawlers are worth separating — the training
 * crawlers (GPTBot, ClaudeBot) are a different decision from the search and
 * user-initiated ones (OAI-SearchBot, Claude-SearchBot, ChatGPT-User,
 * Claude-User) that decide whether this site can be cited in an answer.
 *
 * Tokens taken from the vendors' own documentation, not from third-party
 * lists:
 *   https://developers.openai.com/api/docs/bots
 *   https://support.claude.com/en/articles/8896518
 *   https://developers.google.com/search/docs/crawling-indexing/google-common-crawlers
 *   https://docs.perplexity.ai/guides/bots
 */

const SEARCH_AND_ASSISTANT = [
  'OAI-SearchBot',      // ChatGPT search results
  'ChatGPT-User',       // fetches a page because a person asked
  'Claude-SearchBot',   // Claude search quality
  'Claude-User',        // fetches a page because a person asked
  'PerplexityBot',      // Perplexity indexing
  'Perplexity-User',    // fetches a page because a person asked
];

const TRAINING = [
  'GPTBot',             // OpenAI model training
  'ClaudeBot',          // Anthropic model training
  'Google-Extended',    // Gemini / Vertex training, separate from Googlebot
  'Applebot-Extended',  // Apple model training
];

export const GET: APIRoute = ({ site }) => {
  const sitemap = new URL('sitemap-index.xml', site).href;

  const body = [
    '# Everything here is public and meant to be read.',
    '',
    'User-agent: *',
    'Allow: /',
    '',
    '# Search and assistant crawlers: allowed. Being readable by these is the',
    '# whole point — they decide whether this site can be cited in an answer.',
    ...SEARCH_AND_ASSISTANT.flatMap((agent) => [`User-agent: ${agent}`, 'Allow: /']),
    '',
    '# Training crawlers: also allowed. Listed separately because this is a',
    '# genuinely different decision from the one above, and a future change of',
    '# mind belongs here rather than in the block above.',
    ...TRAINING.flatMap((agent) => [`User-agent: ${agent}`, 'Allow: /']),
    '',
    `Sitemap: ${sitemap}`,
    '',
  ].join('\n');

  return new Response(body, { headers: { 'Content-Type': 'text/plain; charset=utf-8' } });
};
