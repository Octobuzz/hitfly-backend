<template>
  <div>
    <main class="main">
      <aside class="main__aside aside">
        <div class="aside__content">
          <UserCard v-if="desktop || fullPath === '/profile'"/>
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

    fullPath() {
      return this.$route.fullPath;
    }
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./ProfilePage.sass"
/>
