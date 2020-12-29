<template>
  <div :class="['watched-user-list', containerPaddingClass]">
    <ReturnHeader />
    <span class="h2 watched-user-list__header">
      Слежу
    </span>
    <WatchedUserListEntry
      v-for="userId in userIdList"
      :key="userId"
      :user-id="userId"
    />
    <SpinnerLoader
      v-if="isLoading"
      class="watched-user-list__loader"
      size="small"
    />
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import ReturnHeader from '../ReturnHeader.vue';
import WatchedUserListEntry from '../WatchedUserListEntry';

export default {
  components: {
    SpinnerLoader,
    ReturnHeader,
    WatchedUserListEntry
  },

  mixins: [containerPaddingClass],

  props: {
    userIdList: {
      type: Array,
      required: true
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./WatchedUserList.scss"
/>
