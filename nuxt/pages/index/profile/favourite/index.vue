<template>
  <Favourite />
</template>

<script>
import Favourite from '~/components/profile/Favourite';

export default {
  components: {
    Favourite
  },

  beforeRouteLeave(to, from, next) {
    this.$apollo.provider.clients.defaultClient
      .clearStore().then(() => next());
  }
};
</script>
