<template>
  <div>
    <label class="base-input">
      <span :class="['base-input__label', { 'base-input__label_top': focus || !empty }]">
        {{ label }}
      </span>
      <span class="base-input__icon">
        <slot name="icon"/>
      </span>
      <input
        :class="['base-input__input', { 'base-input__input_error': showError }]"
        :value="value"
        @input="emitInput($event)"
        @focus="focus = true"
        @blur="focus = false"
      >
    </label>
    <span
      v-if="showError"
      class="base-input__error-message"
    >
      {{ errorMessage }}
    </span>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: null
    },
    showError: {
      type: Boolean,
      default: false
    },
    errorMessage: {
      type: String,
      default: null
    },
    value: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      empty: true,
      focus: false
    };
  },
  mounted() {
    this.empty = this.value.length === 0;
  },
  methods: {
    // eslint-disable-next-line func-names
    emitInput(e) {
      this.$emit('input', e.target.value);
      this.empty = e.target.value === '';
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../sass/variables';

.base-input {
  font-family: "Gotham Pro", serif;
  display: block;
  position: relative;
  height: 50px;

  &__label {
    font-size: 14px;
    color: $color_3;
    position: absolute;
    top: 16px;
    left: 17px;
    user-select: none;
    cursor: pointer;
    transition: transform 0.3s ease;

    &_top {
      transform: translate(-13%, -10px) scale(.75);
    }
  }

  &__input {
    box-sizing: border-box;
    font-size: 14px;
    text-align: left;
    display: block;
    width: 100%;
    padding: 22px 32px 10px 16px;
    cursor: pointer;
    border: 1px solid $borderColor;
    border-radius: $border-radius;
    background: $color_2;

    &:focus {
      outline: none;
    }

    &_error {
      border-color: #d0021b;
    }
  }

  &__icon {
    display: block;
    position: absolute;
    width: 16px;
    height: 16px;
    left: 100%;
    top: 50%;
    z-index: 900;
    transform: translate(-180%, -50%);
  }

  &__error-message {
    color: #d0021b;
    font-size: 10px;
  }
}
</style>
