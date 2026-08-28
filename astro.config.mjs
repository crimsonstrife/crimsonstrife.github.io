// @ts-check
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';
import icon from 'astro-icon';

// https://astro.build/config
export default defineConfig({
  // Custom domain served from the repo root, so no `base` is set.
  site: 'https://www.patrickbarnhardt.info',

  integrations: [
    // Inlines only the icons actually referenced in components, as SVG.
    icon(),
    sitemap(),
  ],

  image: {
    // Portfolio thumbnails are the bulk of the payload; bias toward smaller files.
    service: {
      entrypoint: 'astro/assets/services/sharp',
      config: {
        webp: { effort: 5 },
      },
    },
  },
});
