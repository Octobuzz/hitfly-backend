<template>
  <TwoColumnLayout>
    <template #left-column="{ itemContainerClass }">
      <UserCard
        v-if="renderUserCard"
        :item-container-class="itemContainerClass"
      />
    </template>
    <template #right-column="{ paddingClass }">
      <router-view :container-padding-class="paddingClass" />
    </template>
  </TwoColumnLayout>
</template>

<script>
import TwoColumnLayout from 'components/TwoColumnLayout.vue';
import UserCard from './UserCard';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    TwoColumnLayout,
    UserCard
  },
  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    renderUserCard() {
      const { desktop, $route: { fullPath } } = this;

      return desktop || (
        fullPath !== '/profile/edit'
          && fullPath !== '/profile/update-group'
          && fullPath !== '/profile/create-group'
      );
    }
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./ProfilePage.sass"
/>
