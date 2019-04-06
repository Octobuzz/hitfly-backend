<template>
  <div>
    <label class="base-textarea">
      <span
        :class="[
          'base-textarea__label',
          {
            'base-textarea__label_top': focus || !empty,
            ...$attrs.class
          }
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
    // cause empty string is not an input this check is done manually

    value(val) {
      if (val === '') this.empty = true;
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
@import '../../sass/variables';

.base-textarea {
  font-family: "Gotham Pro", serif;
  display: block;
  position: relative;

  &__label {
    font-size: 14px;
    color: $color_3;
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

  &__textarea {
    box-sizing: border-box;
    font-size: 14px;
    text-align: left;
    display: block;
    width: 100%;
    height: 100%;
    padding: 22px 32px 10px 16px;
    cursor: pointer;
    border: 1px solid $borderColor;
    border-radius: $border-radius;
    background: $color_2;
    resize: none;

    &:focus {
      outline: none;
    }

    &_error {
      border-color: #d0021b;
    }
  }

  &__error-message {
    color: #d0021b;
    font-size: 10px;
  }
}
</style>
