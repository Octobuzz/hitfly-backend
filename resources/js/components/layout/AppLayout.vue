<template>
  <ProfileDataProvider>
    <div>
      <portal-target name="root" />
      <IconGradientRadial />
      <FlashMessage />
      <Page404 v-if="if404" />
      <AppHeader :class="{ 'app-layout_blurred': blurred }" v-if="!if404" />
      <router-view :class="{ 'app-layout_blurred': blurred }" v-if="!if404" />
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
import Page404 from '../../pages/Page404.vue';

export default {
  components: {
    ProfileDataProvider,
    IconGradientRadial,
    FlashMessage,
    AppHeader,
    AppFooter,
    Page404
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
