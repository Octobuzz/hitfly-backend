import { ApolloClient } from 'apollo-client';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import Vue from 'vue';
import VueApollo from 'vue-apollo';

Vue.use(VueApollo);

const prod = process.env.NODE_ENV === 'production';

const uri = prod
  ? `${process.env.APP_URL}/graphql/user`
  : 'http://localhost:9090/graphql/user';

// TODO: set token properly

const headers = {
  'X-TOKEN-AUTH': 'e263aad159789d432e0508b5713295ac'
};

const httpLink = new HttpLink({
  uri,
  headers
});

// TODO: second endpoint

const cache = new InMemoryCache();

const apolloClient = new ApolloClient({
  link: httpLink,
  cache,
  connectToDevTools: !prod
});

export default new VueApollo({
  defaultClient: apolloClient
});
