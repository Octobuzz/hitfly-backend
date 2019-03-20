module.exports = {
  root: true,
  parserOptions: {
    sourceType: 'module',
    parser: 'babel-eslint'
  },
  env: {
    node: true
  },
  extends: [
    'plugin:vue/recommended',
    '@vue/airbnb'
  ],
  plugins: [
    'vue'
  ],
  settings: {
    'import/resolver': {
      webpack: require.resolve('laravel-mix/setup/webpack.config.js')
    }
  },
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'comma-dangle': 'off',
    'vue/max-attributes-per-line': [2,
      {
        'singleline': 2,
        'multiline': {
          'max': 1,
          'allowFirstLine': false
        }
      }
    ],
  }
};
