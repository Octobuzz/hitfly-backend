<template>
  <span
    :class="['base-tag', { 'base-tag_active': active }]"
    @click="onClick($event)"
  >
    <NoteIcon/>
    {{ tag }}
  </span>
</template>

<script>
import NoteIcon from './icons/NoteIcon.vue';

export default {
  components: {
    NoteIcon
  },
  props: {
    tag: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      active: false
    };
  },
  methods: {
    onClick(e) {
      this.active = !this.active;
      this.$emit('press', {
        tag: this.tag,
        active: this.active
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./BaseTag.scss"
/>
