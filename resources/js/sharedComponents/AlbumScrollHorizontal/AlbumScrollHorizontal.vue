<template>
  <div
    ref="root"
    class="album-scroll-horizontal"
  >
    <div>
      <button
        class="album-scroll-horizontal__button-prev"
        @click="goBack"
      >
        prev
      </button>
      <button
        class="album-scroll-horizontal__button-next"
        @click="goForward"
      >
        next
      </button>
    </div>
    <swiper ref="swiper" :options="swiperOptions">
      <swiper-slide
        v-for="albumId in albumIdList"
        :key="albumId"
      >
        <AlbumPreview :album-id="albumId" />
      </swiper-slide>
      <swiper-slide
        v-if="hasMoreData"
        key="loader"
        class="album-scroll-horizontal__loader"
      >
        loading...
      </swiper-slide>
    </swiper>
  </div>
</template>

<script>
import {
  swiper as Swiper,
  swiperSlide as SwiperSlide
} from 'vue-awesome-swiper';
import 'swiper/dist/css/swiper.css';
import AlbumPreview from 'components/AlbumPreview';

const ALBUM_WIDTH = 120;
const ALBUM_SPACE_WIDTH_MOBILE = 16;
const ALBUM_SPACE_WIDTH_DESKTOP = 24;

export default {
  components: {
    Swiper,
    SwiperSlide,
    AlbumPreview
  },

  props: {
    albumIdList: {
      type: Array,
      required: true
    },
    isLoading: {
      type: Boolean,
      required: true
    },
    hasMoreData: {
      type: Boolean,
      required: true
    }
  },

  data() {
    return {
      swiperOptions: {
        slidesPerView: 'auto',
        spaceBetween: ALBUM_SPACE_WIDTH_DESKTOP,
        breakpoints: {
          767: {
            spaceBetween: ALBUM_SPACE_WIDTH_MOBILE
          }
        },
        on: {
          slideChange: () => {
            this.onSlideChange();
          }
        }
      }
    };
  },

  computed: {
    root() {
      return this.$refs.root;
    },

    swiper() {
      return this.$refs.swiper.swiper;
    },

    visibleItemsCount() {
      const { spaceBetween } = this.swiper.params;
      const containerWidth = this.root.clientWidth;

      let visibleAlbumsCount = -1;
      let visibleAlbumsWidth = 0;

      while (visibleAlbumsWidth - spaceBetween < containerWidth) {
        visibleAlbumsCount += 1;
        visibleAlbumsWidth += ALBUM_WIDTH + spaceBetween;
      }

      return visibleAlbumsCount;
    },

    navButtonSlideCount() {
      return this.visibleItemsCount;
    },

    // relatively to swiper.activeIndex

    slidesRemainBeforeLoading() {
      return 2 * this.visibleItemsCount;
    }
  },

  methods: {
    onSlideChange() {
      const {
        albumIdList,
        swiper,
        slidesRemainBeforeLoading,
        hasMoreData,
        isLoading
      } = this;

      const slidesRemain = albumIdList.length - (swiper.activeIndex + 1);

      if (slidesRemainBeforeLoading >= slidesRemain
          && hasMoreData
          && !isLoading) {
        this.$emit('load-more');
      }
    },

    goBack() {
      const { swiper, navButtonSlideCount } = this;

      swiper.slideTo(swiper.activeIndex - navButtonSlideCount);
    },

    goForward() {
      const {
        swiper,
        navButtonSlideCount,
      } = this;

      swiper.slideTo(swiper.activeIndex + navButtonSlideCount);
    }
  }
};
</script>

<style lang="scss">
.album-scroll-horizontal {
  text-align: right;

  &__button-prev {

  }

  &__button-next {

  }

  &__loader {
    text-align: left;
    box-sizing: border-box;
    border: 10px solid black;
  }

  .swiper-slide {
    width: 120px;
  }

  .swiper-slide-next {
    width: 120px;
  }

  .swiper-slide-active {
    width: 120px;
  }
}
</style>
