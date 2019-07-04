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
        {{ message }}
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
  name: 'NotificationRegular',

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
    message() {
      return this.data.messageData.message;
    },

    dateText() {
      const date = new Date(this.data.date);

      return date.toLocaleString();
    },
  }
};
</script>
