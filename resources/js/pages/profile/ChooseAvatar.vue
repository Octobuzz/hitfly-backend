<template>
  <div :class="['avatar', { avatar_circle: circle }]">
    <transition>
      <div
        v-if="renderInFirstContainer && url"
        class="avatar__image"
        :alt="alt"
        :style="{ backgroundImage: `url(${url})` }"
        :class="{ 'avatar__image_top': renderInFirstContainer }"
      />
    </transition>
    <transition>
      <div
        v-if="!renderInFirstContainer && url"
        class="avatar__image"
        :alt="alt"
        :style="{ backgroundImage: `url(${url})` }"
        :class="{ 'avatar__image_top': !renderInFirstContainer }"
      />
    </transition>
    <label class="avatar__upload-button">
      <CameraIcon/>
      <span class="avatat__text">
        {{ caption }}
      </span>
      <input
        class="avatar__input"
        type="file"
        accept=".jpg, jpeg, .png"
        @change="setAvatar"
      >
    </label>
  </div>
</template>

<script>
import CameraIcon from '../../sharedComponents/icons/CameraIcon.vue';

export default {
  components: {
    CameraIcon
  },
  props: {
    imageUrl: {
      type: String,
      default: ''
    },
    alt: {
      type: String,
      default: ''
    },
    caption: {
      type: String,
      default: 'Загрузить аватар'
    },
    circle: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      fileReader: new FileReader(),
      inputUrl: '',
      renderInFirstContainer: true
    };
  },

  computed: {
    url() {
      return this.inputUrl || this.imageUrl;
    }
  },

  created() {
    this.fileReader.onload = (e) => {
      this.renderInFirstContainer = !this.renderInFirstContainer;
      this.inputUrl = e.target.result;
    };
  },

  methods: {
    setAvatar(e) {
      const file = e.target.files[0];

      if (file) {
        // TODO: file size validation

        this.fileReader.readAsDataURL(file);
        this.$emit('input', file);
      }
    },

    clearUserInput() {
      this.inputUrl = null;
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../../sass/variables';

.avatar {
  display: flex;
  justify-content: center;
  position: relative;
  height: 100%;
  overflow: hidden;
  background: $gradient;
  border-radius: $border-radius;

  &:before {
    content: '';
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 100%;
    height: 100%;
    border: 1px solid $color_pink;
    border-radius: $border-radius;
    background: rgba(255, 255, 255, 0.7);
  }

  &__image {
    position: absolute;
    width: 100%;
    height: 100%;
    background: {
      position: center;
      repeat: no-repeat;
      size: cover;
    }

    &_top {
      z-index: 5;
    }
  }

  &__input {
    display: none;
  }

  &__upload-button {
    box-sizing: border-box;
    font-family: "Gotham Pro", serif;
    font-size: 12px;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 32px;
    padding: {
      left: 16px;
      right: 16px;
    };
    margin-top: 72%;
    border-radius: $border-radius;
    z-index: 10;
    cursor: pointer;
    user-select: none;
    background: #313131;

    svg {
      margin-right: 8px;
      transform: translateY(-1px);
    }
  }

  &_circle {
    background: none;
    border-radius: 50%;
    overflow: hidden;

    &:before {
      display: none;
    }
  }
}

.v-enter,
.v-leave-to {
  opacity: 0;
}

.v-enter-active {
  transition: opacity .3s ease-out;
}

.v-leave-active {
  transition: opacity .3s ease-in;
}
</style>
