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
        Вы выполнили задание

        <router-link
          :to="bonusProgramLink"
          class="notification-body__text_highlighted"
        >
          {{ taskTitle }}
        </router-link>

        и получили {{ taskBonus }} бонусов
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
  name: 'NotificationCompletedTask',

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

    taskTitle() {
      return this.messageData.task.title;
    },

    taskBonus() {
      return this.messageData.task.bonus;
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
