<template>
  <div
    :class="['notification-body', $attrs.class]"
  >
    <img
      :src="messageData.user.avatar"
      alt="User avatar"
      class="notification-body__image"
    >
    <div class="notification-body__content">
      <span class="notification-body__text">
        {{ messageText }}

        <router-link
          :to="musicLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.music.title }}
        </router-link>

        у

        <router-link
          :to="userLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.user.username }}
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
  name: 'NotificationNewMusic',

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
      return `/user/${this.messageData.user.id}/music`;
    },

    musicLink() {
      let path;

      // eslint-disable-next-line default-case
      switch (this.messageData.music.type) {
        case 'Track':
          path = 'track';
          break;

        case 'Album':
          path = 'album';
          break;

        case 'Collection':
          path = 'playlist';
          break;
      }

      const userId = this.messageData.user.id;
      const musicId = this.messageData.music.id;

      if (path === 'tracks') {
        return `/user/${userId}/music/tracks`;
      }

      return `/user/${userId}/${path}/${musicId}`;
    },

    // eslint-disable-next-line consistent-return, vue/return-in-computed-property
    messageText() {
      // eslint-disable-next-line default-case
      switch (this.messageData.music.type) {
        case 'Track':
          return 'Новая песня';

        case 'Album':
          return 'Новый альбом';

        case 'Collection':
          return 'Новый плейлист';
      }
    },

    dateText() {
      const date = new Date(notificationDate(this.data.date));

      return date.toLocaleString();
    },
  }
};
</script>
