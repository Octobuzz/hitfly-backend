const path = require('path');

module.exports = {
  resolve: {
    alias: {
      images: path.resolve('resources', 'images'),
      scss: path.resolve('resources', 'scss'),
      gql: path.resolve('resources', 'gql'),
      components: path.resolve('resources', 'js', 'components')
    }
  },
  module: {
    rules: [
      {
        test: /\.(graphql|gql)$/,
        exclude: /node_modules/,
        loader: 'graphql-tag/loader',
      }
    ],
  }
};
