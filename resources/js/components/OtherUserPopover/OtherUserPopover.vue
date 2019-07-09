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
          {{ ownerIsWatched ? 'Не следить за автором' : 'Следить за автором' }}
        </span>

        <span class="other-user-popover__menu-item">
          <span class="other-user-popover__menu-item-icon">
            <BendedArrowIcon />
          </span>
          Поделиться
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
import followMixin from 'mixins/followMixin';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import UserPlusIcon from 'components/icons/popover/UserPlusIcon.vue';
import BendedArrowIcon from 'components/icons/popover/BendedArrowIcon.vue';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    UserPlusIcon,
    BendedArrowIcon
  },

  mixins: [followMixin('userWatchData', 'user')],

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
      },
      myId: null,
      userWatchData: null
    };
  },

  methods: {
    followMixinCallback() {
      this.$refs.closeButton.click();
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

    userWatchData: {
      query: gql.query.USER_WATCH_DATA,
      variables() {
        return { id: this.userId };
      },
      update({ user }) {
        return user;
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
