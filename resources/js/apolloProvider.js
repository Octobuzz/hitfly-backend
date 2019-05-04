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

// TODO: second endpoint

const cache = new InMemoryCache({
  cacheRedirects: {
    Query: {
      track: (_, args, { getCacheKey }) => (
        getCacheKey({ __typename: 'Track', id: args.id })
      ),
      album: (_, args, { getCacheKey }) => (
        getCacheKey({ __typename: 'Album', id: args.id })
      ),
      collection: (_, args, { getCacheKey }) => (
        getCacheKey({ __typename: 'Collection', id: args.id })
      ),
      musicGroup: (_, args, { getCacheKey }) => (
        getCacheKey({ __typename: 'MusicGroup', id: args.id })
      ),
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
