<template>
  <div>
    <!--TODO: temp-->
    <router-link to="/">
      home
    </router-link>
    <br>
    <router-link to="/profile/create-group">
      create group
    </router-link>
    <br>
    <router-link to="/profile/update-group">
      update group
    </router-link>
    <br>
    <router-link to="/profile/edit">
      edit
    </router-link>
    <br>
    <router-link to="/profile/obsolete">
      obsolete
    </router-link>
    <!--temp-->

    <main class="main">
      <aside class="main__aside aside">
        <div class="aside__content">
          <UserCard/>
        </div>
      </aside>

      <div class="main__profile">
        <!--TODO: temp-->
        <CreateGroup/>
        <router-view/>
      </div>
    </main>
  </div>
</template>

<script>
import UserCard from './UserCard.vue';
// TODO: temp
import EditUser from './EditUser.vue';
import CreateGroup from "./CreateGroup";

export default {
  components: {
    CreateGroup,
    UserCard,
    EditUser // TODO: temp
  },
  data() {
    return {};
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./ProfilePage.sass"
/>
