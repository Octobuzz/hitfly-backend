<template>
  <div
    :class="['notification-body', $attrs.class]"
  >
    <img
      :src="messageData.follower.avatar"
      alt="User avatar"
      class="notification-body__image"
    >
    <div class="notification-body__content">
      <span class="notification-body__text">
        У вас новый поклонник

        <router-link
          :to="userLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.follower.username }}
        </router-link>
      </span>

      <span class="notification-body__date">
        {{ dateText }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotificationNewFollower',

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
      return `/user/${this.messageData.follower.id}/music`;
    },

    dateText() {
      const date = new Date(this.data.date);

      return date.toLocaleString();
    },
  }
};
</script>
