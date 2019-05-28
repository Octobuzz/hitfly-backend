const path = require('path');

module.exports = {
  root: true,
  parserOptions: {
    sourceType: 'module',
    parser: 'babel-eslint'
  },
  env: {
    browser: true,
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
      alias: [
        ['~', path.resolve('nuxt')],
        ['@', path.resolve('nuxt')]
      ]
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
