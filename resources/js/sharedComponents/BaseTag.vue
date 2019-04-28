<template>
  <span
    :class="[
      'base-tag',
      {
        'base-tag_active': active,
        ...$attrs.class
      }
    ]"
    @click="onClick($event)"
  >
    <NoteIcon/>
    {{ name }}
  </span>
</template>

<script>
import NoteIcon from './icons/NoteIcon.vue';

export default {
  components: {
    NoteIcon
  },
  props: {
    name: {
      type: String,
      required: true
    },
    active: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    onClick() {
      this.$emit('press', {
        name: this.name,
        active: !this.active
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../sass/variables';

.base-tag {
  font-family: "Gotham Pro", serif;
  font-size: 12px;
  color: $color_3;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  padding: 8px 18px 8px 18px;
  border: 1px solid $color_3;
  border-radius: 20px;
  cursor: pointer;
  user-select: none;
  transition: .2s;

  &:hover {
    border-color: $color_purple;
  }

  &::v-deep svg {
    display: block;
    padding-right: 8px;

    & use {
      fill: $color_3;
    }
  }

  &_active {
    color: white;
    border: 1px solid transparent;
    background: $linear-gradient;

    &::v-deep svg use {
      fill: white;
    }
  }
}
</style>
