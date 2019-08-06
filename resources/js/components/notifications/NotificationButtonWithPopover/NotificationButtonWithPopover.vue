<template>
  <span class="notification-button">
    <span
      v-if="unreadCount > 0"
      class="notification-button__tips"
    >
      {{ unreadCount }}
    </span>
    <NotificationPopover
      @show="popoverOnOpen"
      @hide="popoverOnClose"
    >
      <IconButton style="display: block">
        <BellIcon />
      </IconButton>

      <template #popover>
        <div v-if="queue.length > 0">
          <div
            v-for="notification in queue"
            :key="notification.id"
          >
            <component
              :is="'Notification' + pascalCase(notification.data.type)"
              :class="
                [
                  'notification-body_popover',
                  'notification-button__notification',
                  {
                    'notification-button__notification_new': !observedNotifications[notification.id]
                  }
                ]
              "
              :data="notification.data"
            />

            <div class="notification-button__delimiter" />
          </div>
        </div>
        <div v-else class="notification-button__message">
          У вас нет новых уведомлений
        </div>
      </template>
    </NotificationPopover>
  </span>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import BellIcon from 'components/icons/BellIcon.vue';
import NotificationPopover from '../NotificationPopover';
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
} from '../notificationBodies';

export default {
  components: {
    IconButton,
    BellIcon,
    NotificationPopover,
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

  data() {
    return {
      popoverIsOpen: false,
      unreadCount: 0,
      queue: [],
      observedNotifications: {}
    };
  },

  created() {
    const {
      $wsTunnel,
      onNotificationInfo,
      onNotification,
      getNotificationInfo,
      onNotificationsRead
    } = this;

    this.boundGetNotificationInfo = getNotificationInfo.bind(this);
    this.boundOnNotificationInfo = onNotificationInfo.bind(this);
    this.boundOnNotification = onNotification.bind(this);

    $wsTunnel.subscribe('ws-open', this.boundGetNotificationInfo);
    $wsTunnel.subscribe('notification-info', this.boundOnNotificationInfo);
    $wsTunnel.subscribe('notification', this.boundOnNotification);

    if ($wsTunnel.getState() === WebSocket.OPEN) {
      this.getNotificationInfo();
    }

    this.boundOnNotificationsRead = onNotificationsRead.bind(this);
    this.$eventBus.$on('notifications-read', this.boundOnNotificationsRead);
  },

  beforeDestroy() {
    const { $wsTunnel } = this;

    $wsTunnel.unsubscribe('ws-open', this.boundGetNotificationInfo);
    $wsTunnel.unsubscribe('notification-info', this.boundOnNotificationInfo);
    $wsTunnel.unsubscribe('notification', this.boundOnNotification);
    this.$eventBus.$off('notifications-read', this.boundOnNotificationsRead);
  },

  methods: {
    onNotificationInfo({ data }) {
      const { unreadCount, unreadNotifications } = data;

      if (this.popoverIsOpen) {
        this.unreadCount = 0;
        this.sendLastViewedNotificationId(
          unreadNotifications[0].id
        );
      } else {
        this.unreadCount = unreadCount;

        if (unreadCount > 0) {
          this.$message(
            'У вас есть непрочитанные уведомления',
            'info',
            { timeout: 2000 }
          );
        }
      }

      unreadNotifications
        .map(notification => ({
          ...notification,
          data: JSON.parse(notification.data)
        }))
        .reverse() // older first
        .forEach(notification => this.queuePush(notification));
    },

    onNotification(data) {
      if (this.popoverIsOpen) {
        this.unreadCount = 0;
        this.sendLastViewedNotificationId(
          data.id
        );
      } else {
        this.unreadCount += 1;
      }

      this.queuePush(data);
    },

    getNotificationInfo() {
      this.$wsTunnel.json({
        type: 'query',
        data: 'get-notification-info'
      });
    },

    onNotificationsRead(lastViewedId) {
      this.unreadCount = 0;
      this.queue.forEach(({ id }) => {
        this.observedNotifications[id] = true;
      });
      this.sendLastViewedNotificationId(
        lastViewedId
      );
    },

    sendLastViewedNotificationId(id) {
      // with final version of api we don't need id anymore

      this.$wsTunnel.json({
        type: 'notification',
        data: {
          type: 'all-read',
        }
      });
    },

    queuePush(data) {
      this.queue.unshift(data);
      this.queue = this.queue.slice(0, 3);
    },

    popoverOnOpen() {
      this.popoverIsOpen = true;

      if (this.unreadCount === 0) return;

      this.unreadCount = 0;
      this.sendLastViewedNotificationId(
        this.queue[0].id,
      );
    },

    popoverOnClose() {
      this.popoverIsOpen = false;

      this.queue.forEach(({ id }) => {
        this.observedNotifications[id] = true;
      });
    },

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
  src="./NotificationButtonWithPopover.scss"
/>
