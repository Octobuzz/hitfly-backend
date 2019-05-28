<template>
  <span :class="['base-tag', $attrs.class]">
    <NoteIcon />
    <span class="base-tag__name">
      {{ name }}
    </span>
    <button class="base-tag_close-button" @click="onClick">
      <CrossIcon />
    </button>
  </span>
</template>

<script>
import NoteIcon from './icons/NoteIcon.vue';
import CrossIcon from './icons/CrossIcon.vue';

export default {
  components: {
    NoteIcon,
    CrossIcon
  },
  props: {
    id: {
      type: Number,
      required: true
    },
    name: {
      type: String,
      required: true
    }
  },
  methods: {
    onClick() {
      this.$emit('close', {
        id: this.id,
        name: this.name
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '@/assets/scss/_variables.scss';

.base-tag {
  font-family: 'Gotham Pro', sans-serif;
  font-size: 12px;
  color: white;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  padding: 8px 12px;
  border-radius: 20px;
  user-select: none;
  transition: .2s;
  background: $gradient-linear;

  &::v-deep svg {
    display: block;
    fill: white;

    & path {
      fill: white;
      transform-origin: 50% 50%;
    }
  }

  &::v-deep svg:last-child path {
    display: block;
    fill: white;
    transform: scale(.8);
  }

  &__name {
    display: block;
    padding: {
      left: 8px;
      right: 12px;
    }
  }

  &__close-button {
    cursor: pointer;
  }
}
</style>
