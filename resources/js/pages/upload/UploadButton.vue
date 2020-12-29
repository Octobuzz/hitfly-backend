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
@import '~scss/_variables.scss';

.form-button {
  box-sizing: border-box;
  position: relative;
  font-family: "Gotham Pro", sans-serif;
  font-size: 14px;
  width: auto;
  border-radius: $input_border_radius;
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
    background: $gradient_linear;

    &:hover {
      color: #231f20;
      padding: 16px 14px 16px 14px;
      border: 1px solid $red_violet;
      background: transparent;
    }
  }

  &_secondary {
    color: #231f20;
    padding: 16px 14px 16px 14px;
    border: 1px solid $red_violet;

    &:hover {
      color: white;
      padding: 17px 15px 17px 15px;
      border: none;
      background: $gradient_linear;
    }
  }
}
</style>
