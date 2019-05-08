<template>
  <div class="two-column-layout">
    <div class="two-column-layout__left-column">
      <slot
        name="left-column"
        :item-container-class="'two-column-layout__left-column-item'"
      />
    </div>
    <div class="two-column-layout__right-column">
      <slot
        name="right-column"
        :padding-class="'two-column-layout__right-column_padding'"
      />
    </div>
  </div>
</template>

<style lang="scss">
@import '~sass/variables';

.two-column-layout {
  display: flex;
  flex-wrap: nowrap;
  align-items: stretch;
  // consider header and footer
  margin: {
    top: 90px;
    bottom: 90px;
  }

  &__left-column {
    box-sizing: border-box;
    min-width: 31.8%;
    width: 31.8%;
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
  .two-column-layout {
    flex-wrap: wrap;
    height: auto;
    // TODO: update after styling header
    margin-top: 107px;

    &__left-column,
    &__right-column {
      width: 100%;
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
  .two-column-layout {
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
