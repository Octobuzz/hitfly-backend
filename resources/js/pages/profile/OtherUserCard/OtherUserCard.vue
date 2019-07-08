<template>
  <div class="user-card">
    <div :class="[itemContainerClass]">
      <div class="user-card__item">
        <img
          class="user-card__profile-avatar"
          :src="user.avatar || anonymousAvatar"
          alt="User avatar"
        >

        <div class="user-card__profile-info">
          <p class="user-card__profile-name">
            {{ user.name }}
          </p>
          <p class="user-card__followers-count">
            {{ user.followersCount || '0' }} подписчиков
          </p>

          <p
            v-if="user.location && user.location.title"
            class="user-card__location"
          >
            {{ user.location.title }}
          </p>
        </div>
      </div>

      <div class="user-card__item">
        <FormButton>
          {{ user.watched ? 'Не следить' : 'Следить' }}
        </FormButton>

        <OtherUserPopover :user-id="userId">
          <IconButton>
            <DotsIcon />
          </IconButton>
        </OtherUserPopover>
      </div>
    </div>

    <div
      v-if="user.activity"
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
        {{ user.activity }}
      </p>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import FormButton from 'components/FormButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import OtherUserPopover from 'components/OtherUserPopover';
import gql from './gql';

export default {
  components: {
    FormButton,
    IconButton,
    DotsIcon,
    OtherUserPopover
  },

  props: {
    itemContainerClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      user: {
        avatar: '',
        name: '',
        location: null,
        followersCount: '',
        activity: '',
        watched: false
      },
      anonymousAvatar
    };
  },

  computed: {
    userId() {
      return +this.$route.params.userId;
    }
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
  },

  apollo: {
    userProfile: {
      query: gql.query.USER,

      variables() {
        return { id: this.userId };
      },

      update({ user }) {
        const {
          avatar,
          username,
          location,
          description,
          roles,
          iWatch
        } = user;

        this.user.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.user.name = username;
        this.user.watched = iWatch;

        if (location && location.title) {
          this.user.location = location;
        }

        if (roles.some(role => role.slug === 'performer')) {
          if (description) {
            this.user.activity = description;
          }
        }

        this.notifyInitialization(true, 'personalInfo');
      },

      error(err) {
        this.notifyInitialization(false, 'personalInfo');

        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../MyUserCard/MyUserCard.scss"
/>
