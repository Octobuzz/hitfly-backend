<template>
  <div>
    <IconGradientRadial />
    <FlashMessage />
    <AppHeader />
    <nuxt-child />
    <AppFooter />
  </div>
</template>

<script>
import IconGradientRadial from '~/components/shared/IconGradientRadial.vue';
import FlashMessage from '~/components/layout/FlashMessage.vue';
import AppHeader from '~/components/layout/AppHeader.vue';
import AppFooter from '~/components/layout/AppFooter.vue';

export default {
  components: {
    IconGradientRadial,
    FlashMessage,
    AppHeader,
    AppFooter
  }
};
</script>
