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
    <!--temp-->
    <UserCard />
    <router-view />
  </div>
</template>

<script>
import UserCard from './UserCard.vue';

export default {
  components: UserCard,
  data() {
    return {};
  }
};
</script>
