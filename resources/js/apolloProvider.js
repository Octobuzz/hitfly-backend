import { ApolloClient } from 'apollo-client';
import { createUploadLink as HttpLink } from 'apollo-upload-client';
import { InMemoryCache, IntrospectionFragmentMatcher } from 'apollo-cache-inmemory';
import introspectionQueryResultData from 'project/graphql-fragment-types.json';
import Vue from 'vue';
import VueApollo from 'vue-apollo';

Vue.use(VueApollo);

const prod = process.env.NODE_ENV === 'production';

const publicUri = prod
  ? '/graphql'
  : 'http://localhost:9090/graphql';
const privateUri = prod
  ? '/graphql/user'
  : 'http://localhost:9090/graphql/user';


const publicHttpLink = new HttpLink({
  uri: publicUri
});
const privateHttpLink = new HttpLink({
  uri: privateUri
});

const regularCacheRedirect = type => (_, args, { getCacheKey }) => (
  getCacheKey({ __typename: type, id: args.id })
);

const fragmentMatcher = new IntrospectionFragmentMatcher({
  introspectionQueryResultData
});

export const cache = new InMemoryCache({
  fragmentMatcher,
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

const sharedOptions = {
  cache,
  credentials: 'include',
  connectToDevTools: !prod
};

const publicClient = new ApolloClient({
  ...sharedOptions,
  link: publicHttpLink
});
const privateClient = new ApolloClient({
  ...sharedOptions,
  link: privateHttpLink
});

export default new VueApollo({
  defaultClient: privateClient,
  clients: {
    public: publicClient,
    private: privateClient
  }
});
