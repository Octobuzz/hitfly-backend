module.exports = ({ req }) => {
  const isClient = process.client;

  const headers = isClient ? {} : {
    cookie: req.headers.cookie
  };

  const regularCacheRedirect = type => (_, args, { getCacheKey }) => (
    getCacheKey({ __typename: type, id: args.id })
  );

  return {
    ssr: !isClient,

    httpEndpoint: isClient
      ? '/graphql/user'
      : 'http://localhost:9090/graphql/user',

    apollo: {
      credentials: 'include'
    },

    httpLinkOptions: { headers },

    inMemoryCacheOptions: {
      freezeResults: true,

      cacheRedirects: {
        Query: {
          track: regularCacheRedirect('Track'),

          album: regularCacheRedirect('Album'),

          collection: regularCacheRedirect('Collection'),

          musicGroup: regularCacheRedirect('MusicGroup'),

          user: regularCacheRedirect('User')
        }
      }
    },

    getAuth: null
  };
};
