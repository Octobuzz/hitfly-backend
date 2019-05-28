<template>
  <div :class="['active-group-members', $attrs.class]">
    <div
      v-for="user in activeMembers"
      :key="user.id"
      class="active-group-members__user-container"
    >
      <img
        class="active-group-members__user-avatar"
        :src="
          user.avatar.filter(
            avatar => avatar.size === 'size_56x56'
          )[0].url || anonymousAvatar
        "
        alt="User avatar"
      >

      <div>
        <p class="active-group-members__user-name">
          {{ user.username }}
        </p>
        <p
          v-if="user.location"
          class="active-group-members__user-location"
        >
          {{ user.location.title }}
        </p>
      </div>

      <button
        class="active-group-members__remove-button"
        @click="$emit('remove-member', user.id)"
      >
        <CrossIcon />
      </button>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from '~/static/anonymous-avatar.png';
import CrossIcon from '~/components/shared/icons/CrossIcon.vue';
import gql from './gql';

export default {
  components: {
    CrossIcon
  },

  props: {
    activeMemberIds: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      activeMembers: [],
      anonymousAvatar
    };
  },

  watch: {
    activeMemberIds() {
      const promises = this.activeMemberIds
        .map(id => (
          this.$apollo.query({
            query: gql.query.USER,
            variables: { id },
          })
        ));

      Promise.all(promises).then((results) => {
        this.activeMembers = results
          .map(({ data: { user } }) => user);
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./ActiveGroupMembers.scss"
/>
