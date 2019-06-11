<template>
  <div>
    <IconGradientRadial />
    <FlashMessage />
    <AppHeader />
    <router-view />
    <AppFooter />
  </div>
</template>

<script>
import IconGradientRadial from 'components/IconGradientRadial.vue';
import FlashMessage from 'components/layout/FlashMessage.vue';
// import AppHeader from 'components/layout/AppHeader.vue';
import AppHeader from '../../pages/Header.vue';
import AppFooter from 'components/layout/AppFooter.vue';

export default {
  components: {
    IconGradientRadial,
    FlashMessage,
    AppHeader,
    AppFooter
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../../../sass/app.scss"
/>
