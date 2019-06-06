<template>
  <BaseDropdown
    :class="$attrs.class"
    :title="$attrs.title || 'Выберете год'"
    :value="value"
    :options="yearList"
    :hide-selected="true"
    :searchable="false"
    :max-height="500"
    @input="$emit('input', $event)"
  />
</template>

<script>
import BaseDropdown from 'components/BaseDropdown.vue';

export default {
  components: {
    BaseDropdown
  },

  props: {
    value: {
      type: [String, Number],
      default: ''
    }
  },

  created() {
    const currentYear = new Date().getFullYear();
    const yearList = [];

    for (let i = 0; i < 50; i += 1) {
      yearList.push(String(currentYear - i));
    }

    this.yearList = yearList;
  },
};
</script>
