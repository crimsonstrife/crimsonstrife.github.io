import { cloudflareTest } from '@cloudflare/vitest-plugin';
import { defineConfig } from 'vitest/config';

export default defineConfig({
  root: new URL('.', import.meta.url).pathname,
  plugins: [
    cloudflareTest({
      wrangler: { configPath: './wrangler.jsonc' },
      miniflare: {
        bindings: {
          TURNSTILE_SECRET_KEY: 'test-secret',
        },
      },
    }),
  ],
  test: {
    include: ['test/**/*.test.ts'],
  },
});
