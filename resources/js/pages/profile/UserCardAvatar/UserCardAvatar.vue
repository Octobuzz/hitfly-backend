<template>
  <div class="user-card-avatar">
    <img
      class="user-card-avatar__img"
      :src="avatarSrc || anonymousAvatar"
      alt="User avatar"
    >
    <div
      v-if="avatarIcon"
      class="user-card-avatar__icon-wrapper"
    >
      <PerformerIcon
        v-if="avatarIcon === 'performer'"
        class="user-card-avatar__icon"
      />
      <CriticIcon
        v-else-if="avatarIcon === 'critic'"
        class="user-card-avatar__icon"
      />
      <ProfCriticIcon
        v-else-if="avatarIcon === 'prof_critic'"
        class="user-card-avatar__icon"
      />
      <StarIcon
        v-else-if="avatarIcon === 'star'"
        class="user-card-avatar__icon"
      />
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import PerformerIcon from 'components/icons/PerformerAvatarIcon.vue';
import CriticIcon from 'components/icons/CriticAvatarIcon.vue';
import ProfCriticIcon from 'components/icons/ProfCriticAvatarIcon.vue';
import StarIcon from 'components/icons/StarAvatarIcon.vue';

export default {
  components: {
    PerformerIcon,
    CriticIcon,
    ProfCriticIcon,
    StarIcon
  },
  props: {
    roles: {
      type: Array,
      required: true
    },
    avatarSrc: {
      type: null,
      default: ''
    }
  },
  data() {
    return {
      anonymousAvatar
    };
  },
  computed: {
    avatarIcon() {
      return [
        'star',
        'prof_critic',
        'critic',
        'performer'
      ].find(
        role => this.roles.includes(role)
      );
    },
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UserCardAvatar.scss"
/>
