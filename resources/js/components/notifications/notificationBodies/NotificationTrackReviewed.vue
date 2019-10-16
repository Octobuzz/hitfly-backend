<template>
  <div
    :class="['notification-body', $attrs.class]"
  >
    <img
      :src="messageData.reviewer.avatar"
      alt="User avatar"
      class="notification-body__image"
    >
    <div class="notification-body__content">
      <span class="notification-body__text">
        <router-link
          :to="userLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.reviewer.username }}
        </router-link>

        оставил(а) отзыв на песню

        <router-link
          :to="trackLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.track.title }}
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

export default {
  name: 'NotificationTrackReviewed',

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

    userLink() {
      return `/user/${this.messageData.reviewer.id}/music`;
    },

    trackLink() {
      return '/profile/my-music/tracks';
    },

    dateText() {
      const date = new Date(notificationDate(this.data.date));

      return date.toLocaleString();
    }
  }
};
</script>
