import {themes as prismThemes} from 'prism-react-renderer';
import type {Config} from '@docusaurus/types';
import type * as Preset from '@docusaurus/preset-classic';

const config: Config = {
  title: 'TeensyPHP',
  tagline: 'A lightweight PHP micro web framework',
  favicon: 'img/favicon.ico',

  future: {
    v4: true,
  },

  url: 'https://teensyphp.com',
  baseUrl: '/',

  organizationName: 'daniel-samson',
  projectName: 'teensyphp',
  trailingSlash: false,

  onBrokenLinks: 'throw',
  onBrokenMarkdownLinks: 'warn',

  i18n: {
    defaultLocale: 'en',
    locales: ['en'],
  },

  presets: [
    [
      'classic',
      {
        docs: {
          sidebarPath: './sidebars.ts',
          editUrl:
            'https://github.com/daniel-samson/teensyphp/tree/documentation/',
        },
        blog: false,
        theme: {
          customCss: './src/css/custom.css',
        },
      } satisfies Preset.Options,
    ],
  ],

  themeConfig: {
    image: 'img/teensy-php-social-card.png',
    colorMode: {
      respectPrefersColorScheme: true,
    },
    navbar: {
      title: 'TeensyPHP',
      logo: {
        alt: 'TeensyPHP Logo',
        src: 'img/logo.svg',
      },
      items: [
        {
          type: 'docSidebar',
          sidebarId: 'tutorialSidebar',
          position: 'left',
          label: 'Docs',
        },
        {
          href: 'https://github.com/daniel-samson/teensyphp',
          label: 'GitHub',
          position: 'right',
        },
      ],
    },
    footer: {
      style: 'dark',
      links: [
        {
          title: 'Docs',
          items: [
            {
              label: 'Getting Started',
              to: '/docs/intro',
            },
          ],
        },
        {
          title: 'More',
          items: [
            {
              label: 'GitHub',
              href: 'https://github.com/daniel-samson/teensyphp',
            },
            {
              label: 'Packagist',
              href: 'https://packagist.org/packages/daniel-samson/teensyphp',
            },
          ],
        },
      ],
      copyright: 'Copyright ' + new Date().getFullYear() + ' Daniel Samson. Built with Docusaurus.',
    },
    prism: {
      theme: prismThemes.github,
      darkTheme: prismThemes.dracula,
      additionalLanguages: ['php', 'bash'],
    },
  } satisfies Preset.ThemeConfig,
};

export default config;
