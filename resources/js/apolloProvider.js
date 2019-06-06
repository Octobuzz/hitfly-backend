import { ApolloClient } from 'apollo-client';
import { createUploadLink as HttpLink } from 'apollo-upload-client';
import { InMemoryCache } from 'apollo-cache-inmemory';
import Vue from 'vue';
import VueApollo from 'vue-apollo';

Vue.use(VueApollo);

const prod = process.env.NODE_ENV === 'production';

const uri = prod
  ? '/graphql/user'
  : 'http://localhost:3000/graphql/user';

const httpLink = new HttpLink({
  uri
});

const regularCacheRedirect = type => (_, args, { getCacheKey }) => (
  getCacheKey({ __typename: type, id: args.id })
);

// TODO: second endpoint

const cache = new InMemoryCache({
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
});

const apolloClient = new ApolloClient({
  cache,
  link: httpLink,
  credentials: 'include',
  connectToDevTools: !prod
});

export default new VueApollo({
  defaultClient: apolloClient
});
