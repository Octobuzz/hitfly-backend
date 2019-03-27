<template>
  <label class="base-dropdown">
    <span :class="['base-dropdown__label', { 'base-dropdown__label_top': !closed || !empty }]">
      {{ header }}
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
    header: {
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
  src="./BaseDropdown.scss"
/>
