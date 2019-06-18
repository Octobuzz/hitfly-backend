const express = require('express');
const proxy = require('http-proxy-middleware');
const { Nuxt, Builder } = require('nuxt');
const config = require('../../nuxt.config');

const app = express();

config.dev = !(process.env.NODE_ENV === 'production');

async function start() {
  const nuxt = new Nuxt(config);

  // Build only in dev mode
  if (config.dev) {
    const builder = new Builder(nuxt);

    await builder.build();
  } else {
    await nuxt.ready();
  }

  app.use((req, res, next) => {
    console.log(req.cookie);

    next();
  });

  const proxyApi = proxy({
    target: 'http://localhost:9090'
  });

  app.use('/graphql/user', proxyApi);
  app.use(nuxt.render);

  const { host, port } = nuxt.options.server;

  app.listen(port, host);
  console.log(`Server listening on http://${host}:${port}`);
}

start();
