<template>
  <div>
    <portal-target name="root" />
    <IconGradientRadial />
    <FlashMessage />
    <AppHeader :class="{ 'app-layout_blurred': blurred }"/>
    <router-view :class="{ 'app-layout_blurred': blurred }" />
    <AppFooter :class="{ 'app-layout_blurred': blurred }" />
  </div>
</template>

<script>
import IconGradientRadial from 'components/IconGradientRadial.vue';
import FlashMessage from 'components/layout/FlashMessage.vue';
import AppHeader from 'components/layout/AppHeader';
import AppFooter from 'components/layout/AppFooter.vue';

export default {
  components: {
    IconGradientRadial,
    FlashMessage,
    AppHeader,
    AppFooter
  },
  computed: {
    blurred() {
      return this.$store.getters['layout/blurred'];
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../../../sass/app.scss"
/>

<style
  scoped
  lang="scss"
>
.app-layout_blurred {
  filter: blur(5px);
}
</style>
