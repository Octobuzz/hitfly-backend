<template>
  <div
    :class="['notification-body', $attrs.class]"
  >
    <img
      :src="messageData.track.cover"
      alt="User avatar"
      class="notification-body__image"
    >
    <div class="notification-body__content">
      <span class="notification-body__text">
        Поздравляем! Ваша песня

        <router-link
          :to="trackLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.track.title }}
        </router-link>

        попала в топ {{ messageData.top }}
      </span>

      <span class="notification-body__date">
        {{ dateText }}
      </span>
    </div>
  </div>
</template>

<script>
import notificationDate from 'modules/notification-date';

export default {
  name: 'NotificationTrackInTop',

  props: {
    data: {
      type: Object,
      required: true
    }
  },

  computed: {
    messageData() {
      return this.data.messageData;
    },

    trackLink() {
      return '/profile/my-music/tracks';
    },

    dateText() {
      const date = new Date(notificationDate(this.data.date));

      return date.toLocaleString();
    },
  }
};
</script>
