<template>
  <div class="user-card">
    <div :class="[itemContainerClass]">
      <div class="user-card__item">
        <div class="user-card__profile-avatar">
          <img
            class="user-card__profile-avatar-img"
            :src="user.avatar || anonymousAvatar"
            alt="User avatar"
          >
        </div>

        <div class="user-card__profile-info">
          <p class="user-card__profile-name">
            <WordTrimmedWithTooltip
              :word="user.username"
            />
          </p>
          <p class="user-card__followers-count">
            {{ user.followersCount || '0' }}
            {{ format('followers', user.followersCount || '0') }}
          </p>

          <p
            v-if="user.location && user.location.title"
            class="user-card__location"
          >
            {{ user.location.title }}
          </p>
          <p
            v-if="playedGenres"
            class="user-card__played-genres-container"
          >
            <span class="user-card__played-genres-icon">
              <NoteIcon />
            </span>
            <span class="user-card__played-genres">
              {{ playedGenres }}
            </span>
          </p>
        </div>
      </div>

      <div class="user-card__item other-user-card__button-container">
        <UnauthenticatedPopoverWrapper placement="right">
          <template #auth-content>
            <FormButton
              class="other-user-card__follow-button"
              :modifier="ownerIsWatched ? 'primary' : 'secondary'"
              @press="onWatchOwnerPress"
            >
              {{ ownerIsWatched ? 'Слежу' : 'Следить' }}
            </FormButton>
          </template>

          <template #unauth-popover-trigger>
            <FormButton
              class="other-user-card__follow-button"
              modifier="secondary"
            >
              Следить
            </FormButton>
          </template>
        </UnauthenticatedPopoverWrapper>

        <UnauthenticatedPopoverWrapper placement="right">
          <template #auth-content>
            <OtherUserPopover :user-id="userId">
              <IconButton modifier="squared bordered">
                <DotsIcon />
              </IconButton>
            </OtherUserPopover>
          </template>

          <template #unauth-popover-trigger>
            <IconButton modifier="squared bordered">
              <DotsIcon />
            </IconButton>
          </template>
        </UnauthenticatedPopoverWrapper>
      </div>
    </div>

    <div
      :class="[
        'user-card__bonus-program',
        itemContainerClass
      ]"
    >
      <span class="user-card__bonus-program-followers">
        <BpFollowers />
        <span>
          {{ user.followersCount || '0' }}
          {{ format('followers', user.followersCount || '0') }}
        </span>
      </span>
      <span class="user-card__bonus-program-days">
        <BpDaysPassed />
        <span>
          {{ user.bpDaysInProgram || '0' }}
          {{ format('days', user.bpDaysInProgram || '0') }}
        </span>
      </span>
      <span class="user-card__bonus-program-favourites">
        <BpFavouriteTracks />
        {{ user.favouritesTrackCount }}
        {{ format('favourites', user.favouritesTrackCount) }}
      </span>
    </div>

    <div
      v-if="user.description"
      :class="[
        itemContainerClass,
        'user-card__item',
        'user-card__about'
      ]"
    >
      <div class="user-card__item-header">
        <span>
          Обо мне
        </span>
      </div>
      <p class="user-card__about-text">
        {{ user.description }}
      </p>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import followMixin from 'mixins/followMixin';
import anonymousAvatar from 'images/anonymous-avatar.png';
import WordTrimmedWithTooltip from 'components/WordTrimmedWithTooltip';
import FormButton from 'components/FormButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import BpFollowers from 'components/icons/BpFollowers.vue';
import BpDaysPassed from 'components/icons/BpDaysPassed.vue';
import BpFavouriteTracks from 'components/icons/BpFavouriteTracks.vue';
import NoteIcon from 'components/icons/NoteIconGrey.vue';
import OtherUserPopover from 'components/OtherUserPopover';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import gql from './gql';

const formatting = {
  followers: {
    1: 'поклонник',
    234: 'поклонника',
    567890: 'поклонников'
  },
  days: {
    1: 'день в Hitfly',
    234: 'дня в Hitfly',
    567890: 'дней Hitfly'
  },
  favourites: {
    1: 'любимая песня',
    234: 'любимые песни',
    567890: 'любимых песен'
  }
};

export default {
  components: {
    WordTrimmedWithTooltip,
    FormButton,
    IconButton,
    DotsIcon,
    NoteIcon,
    BpFollowers,
    BpDaysPassed,
    BpFavouriteTracks,
    OtherUserPopover,
    UnauthenticatedPopoverWrapper,
  },

  mixins: [followMixin('user', 'user')],

  props: {
    itemContainerClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      anonymousAvatar,
      user: {}
    };
  },

  computed: {
    userId() {
      return +this.$route.params.userId;
    },

    playedGenres() {
      const { genresPlay } = this.user;

      if (!genresPlay || genresPlay.length === 0) {
        return null;
      }

      return genresPlay.map(genre => (
        genre.name[0].toUpperCase() + genre.name.slice(1)
      )).join(', ');
    },

    ...mapGetters(['apolloClient', 'isAuthenticated'])
  },

  methods: {
    notifyInitialization(success, key) {
      this.$store.commit('loading/setUserCard', {
        [key]: {
          initialized: true,
          success
        }
      });
    },

    format(type, count) {
      const lastDigit = +count % 10;
      let lastDigitSet = 567890;

      if (lastDigit === 1) {
        lastDigitSet = 1;
      }
      if (lastDigit >= 2 && lastDigit <= 4) {
        lastDigitSet = 234;
      }

      return formatting[type][lastDigitSet];
    }
  },

  apollo: {
    user() {
      return {
        client: this.apolloClient,
        query: gql.query.USER,
        fetchPolicy: 'network-only',

        variables() {
          return {
            id: this.userId,
            isAuthenticated: this.isAuthenticated
          };
        },

        update({ user }) {
          const {
            avatar,
            description,
            roles
          } = user;

          const userAvatar = avatar
            .filter(image => image.size === 'size_56x56')[0].url;

          const slugs = new Set(roles.map(role => role.slug));
          const hasDesc = ['performer', 'critic', 'star']
            .some(slug => slugs.has(slug));

          let activity;

          if (hasDesc && description) {
            activity = description;
          }

          this.notifyInitialization(true, 'personalInfo');

          return {
            ...user,
            avatar: userAvatar,
            description: activity
          };
        },

        error(err) {
          this.notifyInitialization(false, 'personalInfo');

          console.dir(err);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../MyUserCard/MyUserCard.scss"
/>

<style
  scoped
  lang="scss"
  src="./OtherUserCard.scss"
/>
