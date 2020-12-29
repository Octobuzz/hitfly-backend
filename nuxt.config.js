module.exports = {
  mode: 'universal',

  server: { port: 8080 },

  srcDir: 'nuxt',

  head: {
    title: 'Hitfly',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'music service' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  loading: { color: '#fff' },

  css: ['~/assets/scss/main.scss'],

  plugins: [
    { src: '~/plugins/vue-window-size' },
    { src: '~/plugins/v-tooltip', mode: 'client' },
    { src: '~/plugins/vue-flash-message', mode: 'client' },
    { src: '~/plugins/vue-flash-message-mock', mode: 'server' }
  ],

  modules: ['@nuxtjs/apollo'],

  build: {
    transpile: ['gsap/TweenLite'],

    // eslint-disable-next-line no-unused-vars
    extend(wpConfig, ctx) {}
  },

  render: {
    bundleRenderer: {
      shouldPreload(file, type) {
        return (type === 'font' && /\.woff$/.test(file));
      },
      shouldPrefetch(file, type) {
        return ['script', 'style'].includes(type);
      }
    }
  },

  router: {
    middleware: 'history-tracker'
  },

  apollo: {
    errorHandler: '~/plugins/apollo-error-handler',
    clientConfigs: {
      default: '~/plugins/apollo-user-endpoint-config'
    }
  }
};
