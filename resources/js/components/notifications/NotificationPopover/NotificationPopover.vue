<template>
  <v-popover
    popover-base-class="notification-popover"
    popover-wrapper-class="notification-popover__wrapper"
    popover-inner-class="notification-popover__inner"
    popover-arrow-class="notification-popover__arrow"
    placement="bottom-end"
    :popper-options="popperOptions"
    :auto-hide="true"
    @show="$emit('show')"
    @hide="$emit('hide')"
  >
    <slot />

    <template #popover>
      <span class="h5 notification-popover__header">
        Уведомления
      </span>

      <slot name="popover" />

      <router-link
        to="/profile/notifications"
        class="notification-popover__all"
      >
        Все уведомления
      </router-link>

      <button
        ref="closeButton"
        v-close-popover
        style="display: none"
      />
    </template>
  </v-popover>
</template>

<script>
export default {
  data() {
    return {
      popperOptions: {
        modifiers: {
          flip: { enabled: false },
          preventOverflow: {
            enabled: true,
            padding: 20
          }
        }
      }
    };
  },

  watch: {
    '$route.fullPath': function closePopover() {
      this.$refs.closeButton.click();
    }
  }
};
</script>

<style
  lang="scss"
  src="./NotificationPopover.scss"
/>
