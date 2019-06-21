<template>
  <portal to="root">
    <transition name="base-modal">
      <div v-if="open" class="base-modal__mask">
        <div class="base-modal__wrapper" @click.self="emitOpenUpdate(false)">
          <div class="base-modal__container">
            <slot />
          </div>
        </div>
      </div>
    </transition>
  </portal>
</template>

<script>
export default {
  props: {
    open: {
      type: Boolean,
      default: false
    },
    autoClose: {
      type: Boolean,
      default: true
    }
  },

  watch: {
    open: {
      handler(val) {
        // eslint-disable-next-line default-case
        switch (val) {
          case true:
            this.onOpen();
            break;
          case false:
            this.onClose();
            break;
        }
      },
      immediate: true
    }
  },

  methods: {
    onOpen() {
      this.$store.commit('layout/blur', true);
      window.document.body.classList.add('base-modal__body');
      this.$emit('open');
    },

    onClose() {
      this.$store.commit('layout/blur', false);
      window.document.body.classList.remove('base-modal__body');
      this.$emit('close');
    },

    emitOpenUpdate(open) {
      if (this.autoClose === false && open === false) {
        return;
      }

      this.$emit('update:open', open);
    }
  }
};
</script>

<style>
.base-modal__body {
  height: 100vh;
  overflow: hidden;
}
</style>

<style
  scoped
  lang="scss"
>
.base-modal__mask {
  position: fixed;
  display: table;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9998;
  transition: all .3s;
}

.base-modal__wrapper {
  display: table-cell;
  vertical-align: middle;
}

.base-modal__container {
  box-sizing: border-box;
  width: 90%;
  max-width: 800px;
  margin: 0 auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 4px;
  box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
  transition: all .3s ease;
}

.base-modal-enter {
  opacity: 0;
}

.base-modal-leave-active {
  opacity: 0;
}

.base-modal-enter .base-modal-container,
.base-modal-leave-active .base-modal-container {
  transform: scale(1.1);
}
</style>
