<template>
  <ProfileDataProvider>
    <div>
      <portal-target name="root" />
      <IconGradientRadial />
      <FlashMessage />
      <AppHeader :class="{ 'app-layout_blurred': blurred }" v-if="!if404" />
      <router-view :class="{ 'app-layout_blurred': blurred }" />
      <AppFooter :class="{ 'app-layout_blurred': blurred }" v-if="!if404" />
    </div>
  </ProfileDataProvider>
</template>

<script>
import ProfileDataProvider from 'graphql-containers/ProfileDataProvider';
import IconGradientRadial from 'components/IconGradientRadial.vue';
import FlashMessage from 'components/layout/FlashMessage.vue';
import AppHeader from 'components/layout/AppHeader';
import AppFooter from 'components/layout/AppFooter.vue';

export default {
  components: {
    ProfileDataProvider,
    IconGradientRadial,
    FlashMessage,
    AppHeader,
    AppFooter
  },
  computed: {
    if404() {
      return this.$store.getters['appColumns/is404'];
    },
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
