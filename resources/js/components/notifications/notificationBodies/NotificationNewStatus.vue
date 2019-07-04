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
          {{ status }}
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
  name: 'NotificationNewStatus',

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
    status() {
      return this.data.messageData.status.name;
    },

    messageText() {
      return 'Поздравляем! Вы получили новый статус';
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
