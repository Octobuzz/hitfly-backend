<template>
  <div
    :class="[
      'collection-scroll-horizontal',
      $attrs.class
    ]"
  >
    <div
      :class="[
        'collection-scroll-horizontal__header',
        headerClass
      ]"
    >
      <slot name="title" />

      <button
        v-if="!cantGoBack || !cantGoForward"
        :class="[
          'collection-scroll-horizontal__button-prev',
          {
            'collection-scroll-horizontal__button-prev_disabled': cantGoBack
          }
        ]"
        @click="goBack"
      >
        <ArrowIcon />
      </button>

      <button
        v-if="!cantGoBack || !cantGoForward"
        :class="[
          'collection-scroll-horizontal__button-next',
          {
            'collection-scroll-horizontal__button-next_disabled': cantGoForward
          }
        ]"
        @click="goForward"
      >
        <ArrowIcon />
      </button>
    </div>

    <recycle-scroller
      ref="scroller"
      class="collection-scroll-horizontal__scroller"
      direction="horizontal"
      :items="collectionIdList"
      :buffer="3 * (collectionWidth + spaceBetween)"
      :item-size="collectionWidth + spaceBetween"
    >
      <template #default="{ item: id }">
        <CollectionPreview
          class="collection-scroll-horizontal__collection-preview"
          :collection-id="id"
        />
      </template>

      <template #after>
        <span
          v-if="hasMoreData && collectionIdListLength > 0"
          class="collection-scroll-horizontal__loader"
        >
          <BaseLoader :active="hasMoreData" />
        </span>

        <!-- We better ave some place to insert one more item -->
        <span
          v-else
          :style="{ display: 'block', width: desktop ? '262px' : '188px' }" />
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
import CollectionPreview from 'components/CollectionPreview';
import ArrowIcon from 'components/icons/ArrowIcon.vue';

const COLLECTION_WIDTH_DESKTOP = 214;
const COLLECTION_WIDTH_MOBILE = 150;

const COLLECTION_SPACE_BETWEEN_DESKTOP = 24;
const COLLECTION_SPACE_BETWEEN_MOBILE = 16;

const MOBILE_WIDTH = 767;

export default {
  components: {
    RecycleScroller,
    BaseLoader,
    CollectionPreview,
    ArrowIcon
  },

  props: {
    collectionIdList: {
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
      return this.desktop ? COLLECTION_SPACE_BETWEEN_DESKTOP : COLLECTION_SPACE_BETWEEN_MOBILE;
    },

    collectionWidth() {
      return this.desktop ? COLLECTION_WIDTH_DESKTOP : COLLECTION_WIDTH_MOBILE;
    },

    collectionIdListLength() {
      return this.collectionIdList.length;
    }
  },

  watch: {
    tweenedOffset(num) {
      this.$refs.scroller.$el.scrollLeft = num;
    },

    // To check for arrow buttons when collections have being added manually.

    collectionIdList() {
      this.onScroll();
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
      const { scroller, hasMoreData } = this;

      this.cantGoBack = scroller.scrollLeft === 0;
      this.cantGoForward = !hasMoreData
        && (scroller.scrollLeft + scroller.clientWidth) >= this.scroller.scrollWidth;

      this.maybeLoadMore();
    },

    maybeLoadMore() {
      const { scroller, collectionWidth, spaceBetween } = this;
      const collectionSpacedWidth = collectionWidth + spaceBetween;

      if (scroller.scrollWidth - scroller.scrollLeft < 12 * collectionSpacedWidth) {
        this.$parent.$emit('load-more');
      }
    },

    addOffset() {
      const {
        scroller,
        collectionWidth,
        spaceBetween,
        $data
      } = this;

      const spacedCollectionWidth = collectionWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;
      const collectionTimesIncluded = (
        currentOffset + scroller.clientWidth
      ) / spacedCollectionWidth;
      const expectedOffset = spacedCollectionWidth * Math.floor(collectionTimesIncluded);

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
        collectionWidth,
        spaceBetween,
        $data
      } = this;

      const spacedCollectionWidth = collectionWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;

      // BLE - before left edge

      const collectionTimesIncludedBLE = (
        currentOffset + spaceBetween
      ) / spacedCollectionWidth;

      const wholeCollectionsOffsetBLE = spacedCollectionWidth
        * Math.floor(collectionTimesIncludedBLE);

      // edge case means collection is splitted by the left edge
      let edgeCase = true;

      if (wholeCollectionsOffsetBLE + spaceBetween >= currentOffset) {
        edgeCase = false;
      }

      const maxWholeCollectionsVisible = (
        scroller.clientWidth + spaceBetween
      ) / spacedCollectionWidth;

      let expectedOffset = wholeCollectionsOffsetBLE
        - Math.floor(maxWholeCollectionsVisible) * spacedCollectionWidth;

      if (edgeCase) {
        expectedOffset += spacedCollectionWidth;
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
          collectionIdList: { length: collectionCount },
          collectionWidth,
          spaceBetween,
          scroller,
          hasMoreData
        } = this;
        const spacedWidth = collectionWidth + spaceBetween;

        if (collectionCount * spacedWidth > scroller.clientWidth) {
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
  src="./CollectionScrollHorizontal.scss"
/>
