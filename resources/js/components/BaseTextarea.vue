<template>
  <div>
    <label :class="['base-textarea', $attrs.class]">
      <div class="base-textarea__label-backdrop" />
      <span
        :class="[
          'base-textarea__label',
          { 'base-textarea__label_top': focus || !empty }
        ]"
      >
        {{ label }}
      </span>
      <textarea
        :class="['base-textarea__textarea', { 'base-textarea__textarea_error': showError }]"
        :value="value"
        :rows="$attrs.rows"
        @input="emitInput($event)"
        @focus="focus = true"
        @blur="focus = false"
      />
    </label>
    <span
      v-if="showError"
      class="base-textarea__error-message"
    >
      {{ errorMessage }}
    </span>
  </div>
</template>

<script>
// TODO: refactor into mix along with BaseInput

export default {
  props: {
    label: {
      type: String,
      required: true
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

  watch: {
    value(val) {
      this.empty = val === '';
    }
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
@import '~scss/_variables.scss';

.base-textarea {
  font-family: 'Gotham Pro', sans-serif;
  display: block;
  position: relative;

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

  &__label-backdrop {
    position: absolute;
    width: calc(100% - 18px);
    height: 25px;
    top: 1px;
    left: 1px;
    border-top-left-radius: 4px;
    background: linear-gradient(to bottom, white 85%, transparent 100%);
  }

  &__textarea {
    box-sizing: border-box;
    font-size: 14px;
    text-align: left;
    display: block;
    width: 100%;
    height: 100%;
    padding: 22px 32px 10px 16px;
    cursor: pointer;
    border: 1px solid $layout_border_color;
    border-radius: $input_border_radius;
    background: white;
    resize: none;

    &:focus {
      outline: none;
    }

    &_error {
      border-color: $text_color_error;
    }
  }

  &__error-message {
    color: $text_color_error;
    font-size: 12px;
  }
}
</style>
