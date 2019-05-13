<template>
  <div
  :class="[
    'form-button',
    {
      'form-button_secondary': secondary,
      'form-button_primary': primary,
      ...$attrs.class
    }
  ]"
  >
    <p>{{ inputValue }}</p>
    <input
      type="file"
      @change="addFile"
    />
  </div>
</template>

<script>
export default {
  props: {
    inputValue: {
      type: String,
      default: 'Нажмите сюда для загрузки'
    },
    modifier: {
      type: String,
      default: 'primary'
    }
  },
  methods: {
    addFile(e) {
      const file = e.target.files[0];
      this.$emit('changed', file)
    }
  },
  computed: {
    primary() {
      return this.modifier === 'primary';
    },
    secondary() {
      return this.modifier === 'secondary';
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../../sass/variables';

.form-button {
  box-sizing: border-box;
  position: relative;
  font-family: "Gotham Pro", serif;
  font-size: 14px;
  width: auto;
  border-radius: $border-radius;
  cursor: pointer;
  user-select: none;
  transition: color .2s;

  & input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    left: 0;
    top: 0;
    cursor: pointer;
  }

  &_primary {
    color: white;
    padding: 17px 15px 17px 15px;
    border: none;
    background: $linear-gradient;

    &:hover {
      color: $color_6;
      padding: 16px 14px 16px 14px;
      border: 1px solid $color_pink;
      background: transparent;
    }
  }

  &_secondary {
    color: $color_6;
    padding: 16px 14px 16px 14px;
    border: 1px solid $color_pink;

    &:hover {
      color: white;
      padding: 17px 15px 17px 15px;
      border: none;
      background: $linear-gradient;
    }
  }
}
</style>
