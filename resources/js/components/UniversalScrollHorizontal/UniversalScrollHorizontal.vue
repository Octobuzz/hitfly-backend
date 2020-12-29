<template>
  <div
    :class="[
      'universal-scroll-horizontal',
      $attrs.class
    ]"
  >
    <div
      :class="[
        'universal-scroll-horizontal__header',
        headerClass
      ]"
    >
      <slot name="title" />

      <button
        v-if="!cantGoBack || !cantGoForward"
        :class="[
          'universal-scroll-horizontal__button-prev',
          {
            'universal-scroll-horizontal__button-prev_disabled': cantGoBack
          }
        ]"
        @click="goBack"
      >
        <ArrowIcon />
      </button>

      <button
        v-if="!cantGoBack || !cantGoForward"
        :class="[
          'universal-scroll-horizontal__button-next',
          {
            'universal-scroll-horizontal__button-next_disabled': cantGoForward
          }
        ]"
        @click="goForward"
      >
        <ArrowIcon />
      </button>
    </div>

    <recycle-scroller
      ref="scroller"
      class="universal-scroll-horizontal__scroller"
      direction="horizontal"
      :items="itemList"
      :buffer="3 * (itemWidth + spaceBetween)"
      :item-size="itemWidth + spaceBetween"
    >
      <template #default="{ item }">
        <div :style="{ paddingLeft: spaceBetween + 'px' }">
          <slot :item="item" />
        </div>
      </template>
      <template #after>
        <span
          :style="{
            display: 'block',
            width: spaceBetween + 'px'
          }"
        />
      </template>
    </recycle-scroller>
  </div>
</template>

<script>
import debounce from 'lodash.debounce';
import { RecycleScroller } from 'vue-virtual-scroller';
import TweenLite from 'gsap/TweenLite';
import 'vue-virtual-scroller/dist/vue-virtual-scroller.css';
import ArrowIcon from 'components/icons/ArrowIcon.vue';

const MOBILE_WIDTH = 767;

export default {
  components: {
    RecycleScroller,
    ArrowIcon
  },

  props: {
    itemList: {
      type: Array,
      required: true
    },

    headerClass: {
      type: String,
      default: ''
    },

    desktopItemWidth: {
      type: Number,
      required: true
    },

    mobileItemWidth: {
      type: Number,
      required: true
    },

    desktopSpaceBetween: {
      type: Number,
      required: true
    },

    mobileSpaceBetween: {
      type: Number,
      required: true
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
      const {
        desktop,
        desktopSpaceBetween,
        mobileSpaceBetween
      } = this;

      return desktop ? desktopSpaceBetween : mobileSpaceBetween;
    },

    itemWidth() {
      const {
        desktop,
        desktopItemWidth,
        mobileItemWidth
      } = this;

      return desktop ? desktopItemWidth : mobileItemWidth;
    },

    itemListLength() {
      return this.itemList.length;
    }
  },

  watch: {
    tweenedOffset(num) {
      this.$refs.scroller.$el.scrollLeft = num;
    }
  },

  mounted() {
    this.scroller.addEventListener('scroll', this.onScroll);
    window.addEventListener('resize', this.onResize);
    this.onResize();
  },

  destroyed() {
    this.scroller.removeEventListener('scroll', this.onScroll);
    window.removeEventListener('resize', this.onResize);
  },

  methods: {
    handleScroll() {
      const { scroller } = this;

      this.cantGoBack = scroller.scrollLeft === 0;
      this.cantGoForward = (scroller.scrollLeft + scroller.clientWidth)
        >= this.scroller.scrollWidth;
    },

    addOffset() {
      const {
        scroller,
        itemWidth,
        spaceBetween,
        $data
      } = this;

      const spacedAlbumWidth = itemWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;
      const itemTimesIncluded = (
        currentOffset + scroller.clientWidth
      ) / spacedAlbumWidth;
      const expectedOffset = spacedAlbumWidth * Math.floor(itemTimesIncluded);

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
        itemWidth,
        spaceBetween,
        $data
      } = this;

      const spacedAlbumWidth = itemWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;

      // BLE - before left edge

      const itemTimesIncludedBLE = (
        currentOffset + spaceBetween
      ) / spacedAlbumWidth;

      const wholeAlbumsOffsetBLE = spacedAlbumWidth * Math.floor(itemTimesIncludedBLE);

      // edge case means item is splitted by the left edge
      let edgeCase = true;

      if (wholeAlbumsOffsetBLE + spaceBetween >= currentOffset) {
        edgeCase = false;
      }

      const maxWholeAlbumsVisible = (
        scroller.clientWidth + spaceBetween
      ) / spacedAlbumWidth;

      let expectedOffset = wholeAlbumsOffsetBLE
        - Math.floor(maxWholeAlbumsVisible) * spacedAlbumWidth;

      if (edgeCase) {
        expectedOffset += spacedAlbumWidth;
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
          itemList: { length: itemCount },
          itemWidth,
          spaceBetween,
          scroller,
          hasMoreData
        } = this;
        const spacedWidth = itemWidth + spaceBetween;

        if (itemCount * spacedWidth > scroller.clientWidth) {
          this.cantGoBack = scroller.scrollLeft === 0;
          this.cantGoForward = !hasMoreData
            && (scroller.scrollLeft + scroller.clientWidth) >= scroller.scrollWidth;
        } else {
          this.cantGoBack = true;
          this.cantGoForward = true;
        }

        clearInterval(arrowsWatcher);
      }, 50);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UniversalScrollHorizontal.scss"
/>
