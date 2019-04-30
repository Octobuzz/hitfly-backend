<template>
  <div>
    <main class="main">
      <aside class="main__aside aside">
        <div class="aside__content">
          <UserCard v-if="renderUserCard"/>
        </div>
      </aside>

      <div class="main__profile">
        <router-view/>
      </div>
    </main>
  </div>
</template>

<script>
import UserCard from './UserCard';

const MOBILE_WIDTH = 767;

export default {
  components: {
    UserCard
  },
  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    renderUserCard() {
      const { desktop, $route: { fullPath } } = this;

      return desktop || (
        fullPath !== '/profile/edit' && fullPath !== '/profile/update-group'
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
