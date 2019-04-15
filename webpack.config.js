const path = require('path');

module.exports = {
  resolve: {
    alias: {
      images: path.resolve('resources', 'images'),
      sass: path.resolve('resources', 'sass_refactor'),
      components: path.resolve('resources', 'js', 'sharedComponents')
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
