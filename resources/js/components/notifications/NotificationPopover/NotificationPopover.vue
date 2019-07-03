<template>
  <v-popover
    popover-base-class="notification-popover"
    popover-wrapper-class="notification-popover__wrapper"
    popover-inner-class="notification-popover__inner"
    popover-arrow-class="notification-popover__arrow"
    placement="bottom-end"
    :popper-options="popperOptions"
    :auto-hide="true"
  >
    <slot />

    <template #popover>
      <span class="h5 notification-popover__header">
        Уведомления
      </span>
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
  }
};
</script>

<style
  lang="scss"
  src="./NotificationPopover.scss"
/>
