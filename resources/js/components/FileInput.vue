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
        type="file"
        :class="[
          'base-input__input',
          {
            'base-input__input_error': showError,
            'base-input_disabled': disabled
          }
        ]"
        ref="fileInput"
        :disabled="disabled"
        @input="addFile"
        @focus="focus = true"
        @blur="focus = false"
      >
      <span v-if="!empty" class="fileInput__filename">
        <span>{{ fileName }}</span>
        <svg @click="deleteFile" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 475.2 475.2"><path d="M405.6 69.6C360.7 24.7 301.1 0 237.6 0s-123.1 24.7-168 69.6S0 174.1 0 237.6s24.7 123.1 69.6 168 104.5 69.6 168 69.6 123.1-24.7 168-69.6 69.6-104.5 69.6-168-24.7-123.1-69.6-168zm-19.1 316.9c-39.8 39.8-92.7 61.7-148.9 61.7s-109.1-21.9-148.9-61.7c-82.1-82.1-82.1-215.7 0-297.8C128.5 48.9 181.4 27 237.6 27s109.1 21.9 148.9 61.7c82.1 82.1 82.1 215.7 0 297.8z" /><path d="M342.3 132.9c-5.3-5.3-13.8-5.3-19.1 0l-85.6 85.6-85.6-85.6c-5.3-5.3-13.8-5.3-19.1 0-5.3 5.3-5.3 13.8 0 19.1l85.6 85.6-85.6 85.6c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4s6.9-1.3 9.5-4l85.6-85.6 85.6 85.6c2.6 2.6 6.1 4 9.5 4 3.5 0 6.9-1.3 9.5-4 5.3-5.3 5.3-13.8 0-19.1l-85.4-85.6 85.6-85.6c5.3-5.3 5.3-13.8 0-19.1z" /></svg>
      </span>
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
      focus: false,
      fileName: '',
    };
  },

  // watch: {
  //   value(val) {
  //     this.empty = val === '';
  //   }
  // },

  mounted() {
    // this.empty = this.value.length === 0;
  },

  methods: {
    deleteFile(e) {
      e.preventDefault();
      e.stopPropagation();
      const file = null;
      this.$refs.fileInput.value = null;
      this.empty = true;
      this.fileName = '';
      this.$emit('change', file)
    },
    addFile(e) {
      const file = e.target.files[0];
      this.empty = false;
      this.fileName = file.name;
      console.log(this.fileName + ' ' + this.empty);
      this.$emit('change', file)
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '~scss/_variables.scss';

.base-input {
  box-sizing: border-box;
  font-family: 'Gotham Pro', sans-serif;
  display: block;
  position: relative;
  height: 50px;
  border-radius: $input_border_radius;
  border: 1px solid $layout_border_color;

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
    background: white;
    opacity: 0;

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
.fileInput__filename {
  display: -webkit-flex;
  display: -ms-flex;
  display: flex;
  position: absolute;
  left: 20px;
  bottom: 10px;
  -ms-align-items: center;
  align-items: center;

  & svg {
    width: 15px;
    height: auto;
    margin-left: 8px;
    cursor: pointer;
    transition: all .3s;

    &:hover {
      filter: brightness(50%);
    }
  }

  & span {
    font-size: 14px;
  }
}
</style>
