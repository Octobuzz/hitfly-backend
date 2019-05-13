<template>
  <label :class="['base-dropdown', $attrs.class]">
    <span
      :class="[
        'base-dropdown__label',
        {
          'base-dropdown__label_top': !closed || !empty,
        }
      ]"
    >
      {{ title }}
    </span>
    <v-select
      placeholder=""
      :value="value"
      v-bind="$attrs"
      v-on="$listeners"
      @open="closed = false"
      @close="closed = true"
    />
  </label>
</template>

<script>
import VSelect from 'vue-multiselect';

export default {
  components: {
    VSelect
  },

  props: {
    title: {
      type: String,
      default: ''
    },
    value: {
      validator: prop => (
        typeof prop === 'string'
          || prop instanceof Array
          || prop === null
      ),
      required: true
    }
  },

  data() {
    return {
      closed: true,
      empty: true
    };
  },

  mounted() {
    this.redefineEmpty();
  },

  updated() {
    this.redefineEmpty();
  },

  methods: {
    redefineEmpty() {
      const { value } = this;

      if (value instanceof Array) {
        this.empty = value.length === 0;
      }
      if (typeof value === 'string') {
        this.empty = value === '';
      }
      if (value === null) {
        this.empty = true;
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../sass/variables';

.base-dropdown {
  font-family: "Gotham Pro", serif;
  display: block;
  width: 100%;
  position: relative;

  &__label {
    font-size: 14px;
    color: $color_3;
    position: absolute;
    user-select: none;
    cursor: pointer;
    transition: transform 0.3s ease;
    top: 20px;
    left: 16px;
    z-index: -1;

    &_top {
      transform: translate(-13%, -14px) scale(.75);
    }
  }

  .multiselect {
    box-sizing: border-box;
    font-size: 14px;
    text-align: left;
    display: block;
    width: 100%;
    min-height: 50px;
    padding-right: 40px;
    cursor: pointer;
    border: 1px solid $borderColor;
    border-radius: $border-radius;
    background: transparent;

    &--active::v-deep .multiselect {
      &__content-wrapper {
        position: absolute !important;
        width: inherit;
        top: 115%;
        z-index: 900;
        background: white;
        box-shadow: 0 0 10px #f0f0f0;
        overflow-y: auto;
      }

      &__select {
        transform: rotate(180deg);
      }
    }
  }
}

.base-dropdown::v-deep {
  ul {
    width: 100%;
    padding: 0;
    margin: 0;
    list-style: none;

    span {
      box-sizing: border-box;
      display: block;
      width: 100%;
      height: 100%;
      padding: 8px 16px;
    }
  }

  .multiselect {
    &-leave-active {
      display: none;
    }

    &__tags {
      padding: 22px 16px 10px 16px;
    }

    &__element {
      width: 100%;
    }

    &__option {
      padding: 0;

      &--selected {
        color: $color_blue;
      }

      &--highlight {
        color: $color_pink;
      }
    }

    &__select {
      box-sizing: border-box;
      position: absolute;
      width: 35px;
      height: 35px;
      top: 8px;
      right: 8px;
      transition: .3s;
      background: {
        image: url(../../images/dropdown.svg);
        repeat: no-repeat;
        position: center;
      }
    }
  }
}
</style>
