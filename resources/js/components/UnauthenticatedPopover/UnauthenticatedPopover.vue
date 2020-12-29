<template>
  <v-popover
    popover-base-class="unauthenticated-popover"
    popover-wrapper-class="unauthenticated-popover__wrapper"
    popover-inner-class="unauthenticated-popover__inner"
    popover-arrow-class="unauthenticated-popover__arrow"
    :placement="$attrs.placement || 'bottom-center'"
    :auto-hide="true"
  >
    <button
      ref="closeButton"
      style="display: none"
      v-close-popover
    />

    <slot />

    <template #popover>
      <div class="unauthenticated-popover__text">
        Для просмотра этого функционала требуется авторизация
      </div>

      <FormButton
        class="unauthenticated-popover__button"
        modifier="primary"
        @press="gotoLogin"
      >
        Авторизоваться
      </FormButton>
    </template>
  </v-popover>
</template>

<script>
import FormButton from 'components/FormButton.vue';

export default {
  components: {
    FormButton
  },
  methods: {
    gotoLogin() {
      window.location = '/login';
    }
  }
};
</script>

<style
  lang="scss"
  src="./UnauthenticatedPopover.scss"
/>
