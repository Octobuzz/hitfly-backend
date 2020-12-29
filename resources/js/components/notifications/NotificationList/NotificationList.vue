<template>
  <div :class="['notification-list', containerPaddingClass]">
    <span class="h2 notification-list__header">
      Уведомления
    </span>

    <div
      v-for="notification in notifications"
      :key="notification.id"
    >
      <component
        :is="'Notification' + pascalCase(notification.data.type)"
        :class="[
          'notification-body',
          'notification-list__entry',
          { 'notification-list__entry_new': notification.readAt === null }
        ]"
        :data="notification.data"
      />
    </div>

    <SpinnerLoader
      v-show="showLoader"
      class="notification-list__loader"
      size="small"
    />
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import {
  NotificationCompletedTask,
  NotificationNewFollower,
  NotificationNewLevel,
  NotificationNewStatus,
  NotificationNewMusic,
  NotificationRating,
  NotificationReadyToBuyService,
  NotificationRegular,
  NotificationTrackInTop,
  NotificationTrackReviewed
} from 'components/notifications/notificationBodies';

export default {
  components: {
    SpinnerLoader,
    NotificationCompletedTask,
    NotificationNewFollower,
    NotificationNewLevel,
    NotificationNewStatus,
    NotificationNewMusic,
    NotificationRating,
    NotificationReadyToBuyService,
    NotificationRegular,
    NotificationTrackInTop,
    NotificationTrackReviewed
  },

  mixins: [containerPaddingClass],

  props: {
    notifications: {
      type: Array,
      required: true
    },
    showLoader: {
      type: Boolean,
      required: true
    }
  },

  methods: {
    pascalCase(str) {
      const uppercaseFirstLetter = word => (
        word[0].toUpperCase() + word.slice(1)
      );

      return str.split('-')
        .map(word => uppercaseFirstLetter(word))
        .join('');
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./NotificationList.scss"
/>
