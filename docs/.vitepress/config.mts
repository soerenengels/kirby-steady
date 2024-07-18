import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Kirby 🤝  Steady",
  description: "Kirby meets Steady — a Community Plugin",
  lastUpdated: true,
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    search: {
      provider: 'local'
    },
    editLink: {
      pattern: 'https://github.com/soerenengels/kirby-steady/edit/main/docs/:path'
    },
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Docs', link: '/get-started/install-setup' }
    ],

    sidebar: [
      {
        text: 'Get Started',
        /* link: '/get-started', */
        items: [
          { text: 'Installation & Setup', link: '/get-started/install-setup' },
          { text: 'Config', link: '/get-started/config' }
        ]
      },
      {
        text: 'Explore the Panel',
        items: [
          { text: 'Area', link: '/panel/area' },
          { text: 'Reports', link: '/panel/reports' }
        ]
      },
      {
        text: 'Pile up some $blocks',
        items: [
          { text: 'Plans', link: '/blocks/plans' },
          { text: 'Paywall', link: '/blocks/paywall' },
          { text: 'AdBlock Detection', link: '/blocks/adblock-detection' },
          { text: 'Login with Steady', link: '/blocks/login-button' },
        ]
      },
      {
        text: '$steady, ready, go!',
        link: '/steady',
        items: [
          { text: 'Publication', link: '/steady/publication' },
          { text: 'Plans', link: '/steady/plans' },
          { text: 'Subscriptions', link: '/steady/subscriptions' },
          { text: 'Newsletter Subscribers', link: '/steady/newsletter-subscribers' },
          { text: 'Reports', link: '/steady/reports' },
          { text: 'Widgets', link: '/steady/widgets' }
        ]
      }/* ,
      {
        text: 'OAuth',
        items: [
          { text: 'Work to be Done', link: '/oauth' },
        ]
      } */
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/soerenengels/kirby-steady' }
    ]
  }
})
