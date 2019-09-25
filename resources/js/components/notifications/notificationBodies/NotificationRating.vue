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
        <router-link
          :to="userLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.user.username }}
        </router-link>

        {{ messageText }}

        <router-link
          :to="ratedLink"
          class="notification-body__text_highlighted"
        >
          {{ messageData.rated.title }}
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
  name: 'NotificationRating',

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

    ratedLink() {
      let path;

      // eslint-disable-next-line default-case
      switch (this.messageData.rated.type) {
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

      if (path === 'track') {
        return '/profile/my-music/tracks';
      }

      return `/profile/${path}/${this.messageData.rated.id}`;
    },

    messageText() {
      switch (this.messageData.rated.type) {
        case 'Track':
          return 'оценил(а) вашу песню';

        case 'Album':
          return 'оценил(а) ваш альбом';

        case 'Collection':
          return 'оценил(а) ваш плейлист';

        default:
          return 'оценил(а)';
      }
    },

    dateText() {
      const date = new Date(this.data.date);

      return date.toLocaleString();
    }
  }
};
</script>
