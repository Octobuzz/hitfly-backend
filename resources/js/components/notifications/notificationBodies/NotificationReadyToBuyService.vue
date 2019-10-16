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
        Вы накопили {{ serviceCost }} баллов для покупки

        <router-link
          :to="bonusProgramLink"
          class="notification-body__text_highlighted"
        >
          {{ serviceTitle }}
        </router-link>
      </span>

      <span class="notification-body__date">
        {{ dateText }}
      </span>
    </div>
  </div>
</template>

<script>
import notificationDate from 'modules/notification-date';
import logo from 'images/logo.svg';

export default {
  name: 'NotificationReadyToBuyService',

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
    messageData() {
      return this.data.messageData;
    },

    serviceTitle() {
      return this.messageData.service.title;
    },

    serviceCost() {
      return this.messageData.service.cost;
    },

    bonusProgramLink() {
      return '/profile/bonus-program';
    },

    dateText() {
      const date = new Date(notificationDate(this.data.date));

      return date.toLocaleString();
    },
  }
};
</script>
