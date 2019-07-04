<template>
  <div
    :class="['notification-body', $attrs.class]"
  >
    <img
      :src="logo"
      alt="User avatar"
      class="notification-body__logo"
    >
    <div class="notification-body__content">
      <span class="notification-body__text">
        {{ messageText }}

        <router-link
          :to="bonusProgramLink"
          class="notification-body__text_highlighted"
        >
          {{ level }}
        </router-link>
      </span>

      <span class="notification-body__date">
        {{ dateText }}
      </span>
    </div>
  </div>
</template>

<script>
import logo from 'images/logo.svg';

export default {
  name: 'NotificationNewLevel',

  props: {
    data: {
      type: Object,
      required: true
    }
  },

  data() {
    return { logo };
  },

  computed: {
    // eslint-disable-next-line consistent-return, vue/return-in-computed-property
    level() {
      const { level } = this.data.messageData;

      // eslint-disable-next-line default-case
      switch (level) {
        case 'LEVEL_NOVICE':
          return 'Новичек';

        case 'LEVEL_AMATEUR':
          return 'Любитель';

        case 'LEVEL_CONNOISSEUR_OF_THE_GENRE':
          return 'Знаток жанра';

        case 'LEVEL_SUPER_MUSIC_LOVER':
          return 'Супер меломан';
      }
    },

    messageText() {
      return 'Поздравляем! Вы получили новый уровень';
    },

    bonusProgramLink() {
      return '/profile/bonus-program';
    },

    dateText() {
      const date = new Date(this.data.date);

      return date.toLocaleString();
    },
  }
};
</script>
