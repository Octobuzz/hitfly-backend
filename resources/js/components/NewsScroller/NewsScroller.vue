<template>
  <div
    :class="[
      'news-scroll-horizontal',
      $attrs.class
    ]"
  >
    <div
      :class="[
        'news-scroll-horizontal__header',
        headerClass
      ]"
    >
      <slot name="title" />
      <div class="news-scroll-horizontal__button-container">
        <button
          v-if="!cantGoBack || !cantGoForward"
          :class="[
            'news-scroll-horizontal__button-prev',
            {
              'news-scroll-horizontal__button-prev_disabled': cantGoBack
            }
          ]"
          @click="goBack"
        >
          <ArrowIcon />
        </button>

        <button
          v-if="!cantGoBack || !cantGoForward"
          :class="[
            'news-scroll-horizontal__button-next',
            {
              'news-scroll-horizontal__button-next_disabled': cantGoForward
            }
          ]"
          @click="goForward"
        >
          <ArrowIcon />
        </button>
      </div>
    </div>

    <recycle-scroller
      ref="scroller"
      class="news-scroll-horizontal__scroller"
      direction="horizontal"
      :items="newsList"
      :buffer="3 * newsItemWidth"
      :item-size="newsItemWidth"
    >
      <template #default="{ item: item }">
        <NewsPreview
          class="news-scroll-horizontal__news-preview"
          :news-obj="item"
        />
      </template>

      <template #after>
        <span
          v-if="hasMoreData && newsListLength > 0"
          class="news-scroll-horizontal__loader"
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
import NewsPreview from 'components/NewsPreview';
import ArrowIcon from 'components/icons/ArrowIcon.vue';

const NEWS_WIDTH_DESKTOP = 582;
const NEWS_WIDTH_MOBILE = 500;

const NEWS_SPACE_BETWEEN_DESKTOP = 24;
const NEWS_SPACE_BETWEEN_MOBILE = 16;

const MOBILE_WIDTH = 767;

export default {
  components: {
    RecycleScroller,
    BaseLoader,
    NewsPreview,
    ArrowIcon
  },

  props: {
    newsList: {
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
      return this.desktop ? NEWS_SPACE_BETWEEN_DESKTOP : NEWS_SPACE_BETWEEN_MOBILE;
    },

    newsWidth() {
      return this.desktop ? NEWS_WIDTH_DESKTOP : NEWS_WIDTH_MOBILE;
    },

    newsListLength() {
      return this.newsList.length;
    },

    newsItemWidth() {
      return this.desktop ? this.newsWidth + this.spaceBetween : (this.newsWidth + this.spaceBetween) / 2;
    }
  },

  watch: {
    tweenedOffset(num) {
      this.$refs.scroller.$el.scrollLeft = num;
    },

    newsList() {
      this.onScroll();
    }
  },

  mounted() {
    this.boundOnNewsRemoved = this.onNewsRemoved.bind(this);

    this.$eventBus.$on(
      'news-removed',
      this.boundOnNewsRemoved
    );

    this.scroller.addEventListener('scroll', this.onScroll);
    window.addEventListener('resize', this.onResize);
    this.onResize();
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'news-removed',
      this.boundOnNewsRemoved
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
      const { scroller } = this;

      if (scroller.scrollWidth - scroller.scrollLeft < 20 * this.newsItemWidth) {
        this.$parent.$emit('load-more');
      }
    },

    addOffset() {
      const {
        scroller,
        $data
      } = this;

      const spacedNewsWidth = this.newsItemWidth;
      const currentOffset = scroller.scrollLeft;
      const newsTimesIncluded = (
        currentOffset + scroller.clientWidth
      ) / spacedNewsWidth;
      const expectedOffset = spacedNewsWidth * Math.floor(newsTimesIncluded);

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
        spaceBetween,
        $data
      } = this;

      const spacedNewsWidth = this.newsItemWidth;
      const currentOffset = scroller.scrollLeft;

      // BLE - before left edge

      const newsTimesIncludedBLE = (
        currentOffset + spaceBetween
      ) / spacedNewsWidth;

      const wholeNewsOffsetBLE = spacedNewsWidth * Math.floor(newsTimesIncludedBLE);

      let edgeCase = true;

      if (wholeNewsOffsetBLE + spaceBetween >= currentOffset) {
        edgeCase = false;
      }

      const maxWholeNewsVisible = (
        scroller.clientWidth + spaceBetween
      ) / spacedNewsWidth;

      let expectedOffset = wholeNewsOffsetBLE
        - Math.floor(maxWholeNewsVisible) * spacedNewsWidth;

      if (edgeCase) {
        expectedOffset += spacedNewsWidth;
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
          newsList: { length: newsCount },
          scroller,
          hasMoreData
        } = this;
        const spacedWidth = this.newsItemWidth;

        if (newsCount * spacedWidth > scroller.clientWidth) {
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

    onNewsRemoved() {
      const { $data, scroller } = this;

      TweenLite.fromTo(
        $data,
        0.5,
        { tweenedOffset: scroller.scrollLeft },
        { tweenedOffset: 0 }
      );
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./NewsScroller.scss"
/>
