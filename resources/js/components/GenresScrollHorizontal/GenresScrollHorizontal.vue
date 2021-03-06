<template>
  <div
    :class="[
      'album-scroll-horizontal',
      $attrs.class
    ]"
  >
    <div
      :class="[
        'album-scroll-horizontal__header',
        headerClass
      ]"
    >
      <slot name="title" />
      <div class="album-scroll-horizontal__button-container">
        <button
          v-if="!cantGoBack || !cantGoForward"
          :class="[
            'album-scroll-horizontal__button-prev',
            {
              'album-scroll-horizontal__button-prev_disabled': cantGoBack
            }
          ]"
          @click="goBack"
        >
          <ArrowIcon />
        </button>

        <button
          v-if="!cantGoBack || !cantGoForward"
          :class="[
            'album-scroll-horizontal__button-next',
            {
              'album-scroll-horizontal__button-next_disabled': cantGoForward
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
      class="album-scroll-horizontal__scroller"
      direction="horizontal"
      :items="genresList"
      :buffer="3 * (albumWidth + spaceBetween)"
      :item-size="albumWidth + spaceBetween"
    >
      <template #default="{ item: id }">
        <GenrePreview
          class="album-scroll-horizontal__album-preview"
          :genre="id"
        />
      </template>

      <template #after>
        <span
          v-if="hasMoreData && genresListLength > 0"
          class="album-scroll-horizontal__loader"
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
import GenrePreview from 'components/GenrePreview';
import ArrowIcon from 'components/icons/ArrowIcon.vue';

const ALBUM_WIDTH_DESKTOP = 213;
const ALBUM_WIDTH_MOBILE = 200;

const ALBUM_SPACE_BETWEEN_DESKTOP = 24;
const ALBUM_SPACE_BETWEEN_MOBILE = 16;

const MOBILE_WIDTH = 767;

export default {

  components: {
    RecycleScroller,
    BaseLoader,
    GenrePreview,
    ArrowIcon
  },

  props: {
    genresList: {
      type: Array,
      required: true
    },
    hasMoreData: {
      type: Boolean,
      required: false
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
      return this.desktop ? ALBUM_SPACE_BETWEEN_DESKTOP : ALBUM_SPACE_BETWEEN_MOBILE;
    },

    albumWidth() {
      return this.desktop ? ALBUM_WIDTH_DESKTOP : ALBUM_WIDTH_MOBILE;
    },

    genresListLength() {
      return this.genresList.length;
    }
  },

  watch: {
    tweenedOffset(num) {
      this.$refs.scroller.$el.scrollLeft = num;
    },

    // To check for arrow buttons when albums have being added manually.

    genresList() {
      this.onScroll();
    }
  },

  mounted() {
    this.scroller.addEventListener('scroll', this.onScroll);
    window.addEventListener('resize', this.onResize);
    this.onResize();
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
      const { scroller, albumWidth, spaceBetween } = this;
      const albumSpacedWidth = albumWidth + spaceBetween;

      if (scroller.scrollWidth - scroller.scrollLeft < 20 * albumSpacedWidth) {
        this.$parent.$emit('load-more');
      }
    },

    addOffset() {
      const {
        scroller,
        albumWidth,
        spaceBetween,
        $data
      } = this;

      const spacedAlbumWidth = albumWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;
      const albumTimesIncluded = (
        currentOffset + scroller.clientWidth
      ) / spacedAlbumWidth;
      const expectedOffset = spacedAlbumWidth * Math.floor(albumTimesIncluded);

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
        albumWidth,
        spaceBetween,
        $data
      } = this;

      const spacedAlbumWidth = albumWidth + spaceBetween;
      const currentOffset = scroller.scrollLeft;

      // BLE - before left edge

      const albumTimesIncludedBLE = (
        currentOffset + spaceBetween
      ) / spacedAlbumWidth;

      const wholeAlbumsOffsetBLE = spacedAlbumWidth * Math.floor(albumTimesIncludedBLE);

      // edge case means album is splitted by the left edge
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
          genresList: { length: genresCount },
          albumWidth,
          spaceBetween,
          scroller,
          hasMoreData
        } = this;
        const spacedWidth = albumWidth + spaceBetween;

        if (genresCount * spacedWidth > scroller.clientWidth) {
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
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./GenresScrollHorizontal.scss"
/>
