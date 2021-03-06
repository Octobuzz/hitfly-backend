const path = require('path');

module.exports = {
  resolve: {
    alias: {
      project: __dirname,
      images: path.resolve('resources', 'images'),
      scss: path.resolve('resources', 'scss'),
      gql: path.resolve('resources', 'gql'),
      router: path.resolve('resources', 'js', 'router'),
      components: path.resolve('resources', 'js', 'components'),
      modules: path.resolve('resources', 'js', 'modules'),
      mixins: path.resolve('resources', 'js', 'mixins'),
      pages: path.resolve('resources', 'js', 'pages'),
      'graphql-containers': path.resolve('resources', 'js', 'graphql-containers')
    }
  },
  module: {
    rules: [
      {
        test: /\.(graphql|gql)$/,
        exclude: /node_modules/,
        loader: 'graphql-tag/loader'
      }
    ],
  }
};
