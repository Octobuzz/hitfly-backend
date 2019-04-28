<template>
  <div>
    <main class="main">
      <aside class="main__aside aside">
        <div class="aside__content">
          <UserCard/>
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

export default {
  components: {
    UserCard
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./ProfilePage.sass"
/>
