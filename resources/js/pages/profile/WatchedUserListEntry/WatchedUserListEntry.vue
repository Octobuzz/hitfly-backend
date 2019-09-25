<template>
  <div class="watched-user-list-entry">
    <router-link :to="`/user/${userId}/music`">
      <img
        :src="userAvatar || anonymousAvatar"
        alt="Аватар пользователя"
        class="watched-user-list-entry__avatar"
      >
    </router-link>

    <div class="watched-user-list-entry__user-info">
      <router-link
        :to="userLink"
        class="h2 watched-user-list-entry__user-info-name"
      >
        {{ user.username }}
      </router-link>
      <span class="watched-user-list-entry__user-info-followers">
        {{ user.followersCount }}
        {{ formatFollowers(user.followersCount) }}
      </span>
      <span
        v-if="user.location"
        class="watched-user-list-entry__user-info-location"
      >
        <span class="watched-user-list-entry__user-info-icon">
          <BalloonIconGrey />
        </span>
        {{ user.location.title }}
      </span>
      <span
        v-if="userPlayedGenres"
        class="watched-user-list-entry__user-info-genres"
      >
        <span class="watched-user-list-entry__user-info-icon">
          <NoteIconGrey />
        </span>
        {{ userPlayedGenres }}
      </span>
    </div>

    <div class="watched-user-list-entry__button-container">
      <FormButton
        class="watched-user-list-entry__follow-button"
        :modifier="ownerIsWatched ? 'primary' : 'secondary'"
        @press="onWatchOwnerPress"
      >
        {{ ownerIsWatched ? 'Слежу' : 'Следить' }}
      </FormButton>

      <OtherUserPopover
        :user-id="userId"
        placement="left-start"
      >
        <IconButton
          class="watched-user-list-entry__dots-icon"
          passive="standard-passive"
          hover="secondary-hover"
        >
          <DotsIcon />
        </IconButton>
      </OtherUserPopover>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import endingFormatter from 'modules/plural-form-endings-formatter';
import followMixin from 'mixins/followMixin';
import NoteIconGrey from 'components/icons/NoteIconGrey.vue';
import BalloonIconGrey from 'components/icons/BalloonIconGrey.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import IconButton from 'components/IconButton.vue';
import FormButton from 'components/FormButton.vue';
import OtherUserPopover from 'components/OtherUserPopover';
import gql from './gql';

export default {
  components: {
    NoteIconGrey,
    BalloonIconGrey,
    DotsIcon,
    IconButton,
    FormButton,
    OtherUserPopover
  },

  mixins: [followMixin('user', 'user')],

  props: {
    userId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      anonymousAvatar,
      user: {}
    };
  },

  computed: {
    userAvatar() {
      if (!this.user.avatar) {
        return '';
      }

      // TODO: user 72x72 when the api is ready

      return this.user.avatar.find(img => (
        img.size === 'size_56x56'
      )).url;
    },

    userPlayedGenres() {
      if (!this.user.genresPlay) {
        return '';
      }

      return this.user.genresPlay
        .map(genre => genre.name.charAt(0).toUpperCase() + genre.name.slice(1))
        .join(', ');
    },

    userLink() {
      if (!this.user.roles) {
        return '#';
      }

      if (this.user.roles.some(
        role => role.slug === 'performer'
      )) {
        return `/user/${this.userId}/music`;
      }

      return `/user/${this.userId}/user-reviews`;
    }
  },

  methods: {
    formatFollowers(count) {
      return endingFormatter('FOLLOWER', count);
    }
  },

  apollo: {
    user: {
      client: 'private',
      query: gql.query.WATCHED_USER,
      variables() {
        return {
          id: this.userId
        };
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
  scoped
  lang="scss"
  src="./WatchedUserListEntry.scss"
/>
