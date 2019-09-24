<template>
  <div
    :class="[
      'stars-scroll-horizontal',
      $attrs.class
    ]"
  >
    <div
      :class="[
        'stars-scroll-horizontal__header',
        headerClass
      ]"
    >
      <slot name="title" />

      <button
        v-if="!cantGoBack || !cantGoForward"
        :class="[
          'stars-scroll-horizontal__button-prev',
          {
            'stars-scroll-horizontal__button-prev_disabled': cantGoBack
          }
        ]"
        @click="goBack"
      >
        <ArrowIcon />
      </button>

      <button
        v-if="!cantGoBack || !cantGoForward"
        :class="[
          'stars-scroll-horizontal__button-next',
          {
            'stars-scroll-horizontal__button-next_disabled': cantGoForward
          }
        ]"
        @click="goForward"
      >
        <ArrowIcon />
      </button>
    </div>

    <recycle-scroller
      ref="scroller"
      class="stars-scroll-horizontal__scroller"
      direction="horizontal"
      :items="starsIdList"
      :buffer="3 * (starsWidth + spaceBetween)"
      :item-size="starsWidth + spaceBetween"
    >
      <template #default="{ item: id }">
        <StarsPreview
          class="stars-scroll-horizontal__stars-preview"
          :starId="id"
        />
      </template>

      <template #after>
        <span
          v-if="hasMoreData && starsListLength > 0"
          class="stars-scroll-horizontal__loader"
        >
          <BaseLoader :active="hasMoreData" />
        </span>
        <span v-else style="display: block; width: 120px;" />
      </template>
    </recycle-scroller>
  </div>
</template>

<script>
import debounce from 'lodash.debounce';
import { RecycleScroller } from 'vue-virtual-scroller';
import TweenLite from 'gsap/TweenLite';
import 'vue-virtual-scroller/dist/vue-virtual-scroller.css';
import BaseLoader from 'components/BaseLoader.vue';
import StarsPreview from 'components/StarsPreview';
import ArrowIcon from 'components/icons/ArrowIcon.vue';

const STARS_WIDTH_DESKTOP = 200;
const STARS_WIDTH_MOBILE = 150;

const STARS_SPACE_BETWEEN_DESKTOP = 24;
const STARS_SPACE_BETWEEN_MOBILE = 16;

const MOBILE_WIDTH = 767;

export default {
  components: {
    RecycleScroller,
    BaseLoader,
    StarsPreview,
    ArrowIcon
  },

  props: {
    starsIdList: {
      type: Array,
      required: true
    },
    hasMoreData: {
      type: Boolean,
      required: true
    },
    headerClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      tweenedOffset: 0,
      cantGoBack: true,
      cantGoForward: false,
      onScroll: debounce(this.handleScroll.bind(this), 100),
      onResize: this.initArrowWatcher.bind(this)
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    scroller() {
      return this.$refs.scroller.$el;
    },

    spaceBetween() {
      return this.desktop ? STARS_SPACE_BETWEEN_DESKTOP : STARS_SPACE_BETWEEN_MOBILE;
    },

    starsWidth() {
      return this.desktop ? STARS_WIDTH_DESKTOP : STARS_WIDTH_MOBILE;
    },

    starsListLength() {
      return this.starsIdList.length;
    }
  },

  watch: {
    tweenedOffset(num) {
      this.$refs.scroller.$el.scrollLeft = num;
    },

    starsList() {
      this.onScroll();
    }
  },

  mounted() {
    this.boundOnStarsRemoved = this.onStarsRemoved.bind(this);

    this.$eventBus.$on(
      'stars-removed',
      this.boundOnStarsRemoved
    );

    this.scroller.addEventListener('scroll', this.onScroll);
    window.addEventListener('resize', this.onResize);
    this.onResize();
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'stars-removed',
      this.boundOnStarsRemoved
    );

    this.scroller.removeEventListener('scroll', this.onScroll);
    window.removeEventListener('resize', this.onResize);
  },

  methods: {
    handleScroll() {
      const { scroller, hasMoreData } = this;

      this.cantGoBack = scroller.scrollLeft === 0;
      this.cantGoForward = !hasMoreData
        && (scroller.scrollLeft + scroller.clientWidth) >= this.scroller.scrollWidth;

      this.maybeLoadMore();
    },

    maybeLoadMore() {
      const { scroller, starsWidth, spaceBetween } = this;
      const starsSpacedWidth = starsWidth + spaceBetween;

      if (scroller.scrollWidth - scroller.scrollLeft < 20 * starsSpacedWidth) {
        this.$parent.$emit('load-more');
      }
    },

    addOffset() {
      const {
        scroller,
        starsWidth,
        spaceBetween,
        $data
      } = this;

      const spacedStarsWidth = starsWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;
      const starsTimesIncluded = (
        currentOffset + scroller.clientWidth
      ) / spacedStarsWidth;
      const expectedOffset = spacedStarsWidth * Math.floor(starsTimesIncluded);

      TweenLite.fromTo(
        $data,
        0.5,
        { tweenedOffset: currentOffset },
        { tweenedOffset: expectedOffset }
      );
    },

    reduceOffset() {
      const {
        scroller,
        starsWidth,
        spaceBetween,
        $data
      } = this;

      const spacedStarsWidth = starsWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;

      // BLE - before left edge

      const starsTimesIncludedBLE = (
        currentOffset + spaceBetween
      ) / spacedStarsWidth;

      const wholeStarsOffsetBLE = spacedStarsWidth * Math.floor(starsTimesIncludedBLE);

      let edgeCase = true;

      if (wholeStarsOffsetBLE + spaceBetween >= currentOffset) {
        edgeCase = false;
      }

      const maxWholeStarsVisible = (
        scroller.clientWidth + spaceBetween
      ) / spacedStarsWidth;

      let expectedOffset = wholeStarsOffsetBLE
        - Math.floor(maxWholeStarsVisible) * spacedStarsWidth;

      if (edgeCase) {
        expectedOffset += spacedStarsWidth;
      }

      TweenLite.fromTo(
        $data,
        0.5,
        { tweenedOffset: currentOffset },
        { tweenedOffset: expectedOffset }
      );
    },

    goBack() {
      this.reduceOffset();
    },

    goForward() {
      this.addOffset();
    },

    initArrowWatcher() {
      const arrowsWatcher = setInterval(() => {
        if (this.scroller.clientWidth === 0) return;

        const {
          starsIdList: { length: starsCount },
          starsWidth,
          spaceBetween,
          scroller,
          hasMoreData
        } = this;
        const spacedWidth = starsWidth + spaceBetween;

        if (starsCount * spacedWidth > scroller.clientWidth) {
          this.cantGoBack = scroller.scrollLeft === 0;
          this.cantGoForward = !hasMoreData
            && (scroller.scrollLeft + scroller.clientWidth) >= scroller.scrollWidth;
        } else {
          this.cantGoBack = true;
          this.cantGoForward = true;
        }

        clearInterval(arrowsWatcher);
      }, 50);
    },

    onStarsRemoved() {
      const { $data, scroller } = this;

      TweenLite.fromTo(
        $data,
        0.5,
        { tweenedOffset: scroller.scrollLeft },
        { tweenedOffset: 0 }
      );
    }
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./StarsScroller.scss"
/>
