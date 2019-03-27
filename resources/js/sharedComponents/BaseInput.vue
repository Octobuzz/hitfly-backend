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
  src="./BaseInput.scss"
/>
