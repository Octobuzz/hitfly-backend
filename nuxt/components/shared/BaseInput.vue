<template>
  <div class="base-input__wrapper">
    <label :class="['base-input', $attrs.class]">
      <span
        :class="[
          'base-input__label',
          {
            'base-input__label_top': focus || !empty
          }
        ]"
      >
        {{ label }}
      </span>
      <span class="base-input__icon">
        <slot name="icon" />
      </span>
      <input
        :type="password ? 'password' : 'text'"
        :class="[
          'base-input__input',
          {
            'base-input__input_error': showError,
            'base-input_disabled': disabled
          }
        ]"
        :value="value"
        :disabled="disabled"
        @input="emitInput($event)"
        @focus="focus = true"
        @blur="focus = false"
        @keyup.enter="$emit('press:enter')"
      >
    </label>
    <span class="base-input__icon-button">
      <slot name="icon-button" />
    </span>
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
    },
    password: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      empty: true,
      focus: false
    };
  },

  watch: {
    value(val) {
      this.empty = val === '';
    }
  },

  mounted() {
    this.empty = this.value.length === 0;
  },

  methods: {
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
@import '@/assets/scss/_variables.scss';

.base-input {
  box-sizing: border-box;
  font-family: 'Gotham Pro', sans-serif;
  display: block;
  position: relative;
  height: 50px;

  &_disabled {
    background: #f0f0f0 !important;
  }

  &__wrapper {
    position: relative;
  }

  &__label {
    font-size: 14px;
    color: $medium_gray;
    position: absolute;
    top: 20px;
    left: 17px;
    user-select: none;
    cursor: pointer;
    transition: transform 0.3s ease;

    &_top {
      transform: translate(-13%, -14px) scale(.75);
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
    border: 1px solid $layout_border_color;
    border-radius: $input_border_radius;
    background: white;

    &:focus {
      outline: none;
    }

    &_error {
      border-color: #d0021b;
    }
  }

  &__icon {
    display: flex;
    justify-content: center;
    position: absolute;
    width: 16px;
    height: 16px;
    left: 100%;
    top: 50%;
    z-index: 150;
    transform: translate(-200%, -50%);
  }

  &__icon-button {
    display: block;
    position: absolute;
    width: 48px;
    height: 50px;
    top: 0;
    right: 0;
    z-index: 150;
  }

  &__error-message {
    color: #d0021b;
    font-size: 10px;
  }
}
</style>
