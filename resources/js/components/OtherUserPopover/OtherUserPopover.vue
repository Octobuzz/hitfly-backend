<template>
  <v-popover
    popover-base-class="other-user-popover"
    popover-wrapper-class="other-user-popover__wrapper"
    popover-inner-class="other-user-popover__inner"
    popover-arrow-class="other-user-popover__arrow"
    :placement="popover.placement"
    :popper-options="popover.popperOptions"
    :auto-hide="true"
  >
    <slot />

    <button
      ref="closeButton"
      v-close-popover
      style="display: none;"
    />

    <template #popover>
      <div
        v-if="myId"
        class="other-user-popover__menu"
      >
        <!--TODO: use interactive elements-->
        <span
          v-if="myId !== userId"
          class="other-user-popover__menu-item"
          @click="onWatchOwnerPress"
        >
          <span class="other-user-popover__menu-item-icon">
            <UserPlusIcon />
          </span>
          {{ watched ? 'Не следить за автором' : 'Следить за автором' }}
        </span>
      </div>

      <SpinnerLoader
        v-else
        size="small"
      />
    </template>
  </v-popover>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import UserPlusIcon from 'components/icons/popover/UserPlusIcon.vue';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    UserPlusIcon
  },

  props: {
    userId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      popover: {
        placement: 'right-start',
        popperOptions: {}
      }
    };
  },

  methods: {
    onWatchOwnerPress() {
      if (this.watched) {
        this.unsubscribe();
      } else {
        this.subscribe();
      }
    },

    subscribe() {
      console.log('follow');
    },

    unsubscribe() {
      console.log('unfollow');
    }
  },

  apollo: {
    myId: {
      query: gql.query.MY_ID,
      update({ myProfile: { id } }) {
        return id;
      },
      error(err) {
        console.dir(err);
      }
    },

    watched: {
      query: gql.query.AM_I_FOLLOWER,
      variables() {
        return { id: this.userId };
      },
      update({ user: { iWatch } }) {
        return iWatch;
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  lang="scss"
  src="./OtherUserPopover.scss"
/>
