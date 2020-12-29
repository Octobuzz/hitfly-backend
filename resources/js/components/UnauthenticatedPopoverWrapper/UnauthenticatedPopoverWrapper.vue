<template>
  <div>
    <slot
      v-if="isAuthenticated"
      name="auth-content"
    />
    <UnauthenticatedPopover
      v-else
      :placement="$attrs.placement"
    >
      <slot name="unauth-popover-trigger" />
    </UnauthenticatedPopover>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import UnauthenticatedPopover from 'components/UnauthenticatedPopover';

export default {
  components: {
    UnauthenticatedPopover
  },
  computed: {
    ...mapGetters(['isAuthenticated'])
  }
};
</script>
