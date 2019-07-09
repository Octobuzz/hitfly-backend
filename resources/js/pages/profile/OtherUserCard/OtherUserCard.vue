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
            {{ user.username }}
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
        <FormButton
          class="other-user-card__follow-button"
          :modifier="ownerIsWatched ? 'primary' : 'secondary'"
          @press="onWatchOwnerPress"
        >
          {{ ownerIsWatched ? 'Слежу' : 'Следить' }}
        </FormButton>

        <OtherUserPopover :user-id="userId">
          <IconButton modifier="squared bordered">
            <DotsIcon />
          </IconButton>
        </OtherUserPopover>
      </div>
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
import followMixin from 'mixins/followMixin';
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
    user: {
      query: gql.query.USER,

      variables() {
        return { id: this.userId };
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
