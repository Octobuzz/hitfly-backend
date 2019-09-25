<template>
  <div class="app-columns">
    <div class="app-columns__left-column">
      <slot
        name="left-column"
        :item-container-class="'app-columns__left-column-item'"
      />
    </div>
    <div class="app-columns__right-column">
      <slot
        name="right-column"
        :padding-class="'app-columns__right-column_padding'"
      />
    </div>
  </div>
</template>

<script>
export default {
  beforeCreate() {
    this.$store.commit('appColumns/setPaddingClass', 'app-columns__right-column_padding');
  }
};
</script>

<style lang="scss">
@import '~scss/_variables.scss';

.app-columns {
  display: flex;
  flex-wrap: nowrap;
  align-items: stretch;
  padding: {
    top: $header_height_desktop;
    bottom: $footer_height_desktop;
  }

  &__left-column {
    box-sizing: border-box;
    min-width: 31.8%;
    width: 31.8%;
    min-height: calc(100vh - #{$header_height_desktop} - #{$footer_height_desktop});
    border-right: 1px solid $layout_border_color;
  }

  &__left-column-item {
    box-sizing: border-box;
    width: 100%;
    padding: 24px;
    border-bottom: 1px solid $layout_border_color;
  }

  &__right-column {
    box-sizing: border-box;
    flex-grow: 1;
    width: 68.2%;

    &_padding {
      padding: {
        left: 32px;
        right: 32px;
      }
    }
  }
}

@media screen and (max-width: 1024px) {
  .app-columns {
    flex-wrap: wrap;
    height: auto;
    padding-top: $header_height_mobile;

    &__left-column,
    &__right-column {
      width: 100%;
      min-height: auto;
      border: none;
    }

    &__left-column-item {
      padding: {
        left: 32px;
        right: 32px;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .app-columns {
    &__left-column-item {
      padding: 16px;
    }

    &__right-column_padding {
      padding: {
        left: 16px;
        right: 16px;
      }
    }
  }
}
</style>
