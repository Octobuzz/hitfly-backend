<template>
  <div class="album-table">
    <AlbumPreview
      v-for="id in albumIdList"
      :key="id"
      :album-id="id"
    />
  </div>
</template>

<script>
import AlbumPreview from 'components/AlbumPreview';

export default {
  components: {
    AlbumPreview
  },

  props: {
    albumIdList: {
      type: Array,
      required: true
    },
    hasMoreData: {
      type: Boolean,
      required: true
    }
  },

  computed: {
    // TODO: container args depending on the route
  },

  watch: {
    albumIdList: {
      handler() {
        this.$nextTick(() => {
          this.onScroll();
        });
      },
      immediate: true
    }
  },

  mounted() {
    window.addEventListener('scroll', this.onScroll);
  },

  destroyed() {
    window.removeEventListener('scroll', this.onScroll);
  },

  methods: {
    loadMore() {
      this.$parent.$emit('load-more');
    },

    onScroll() {
      const { innerHeight, pageYOffset } = window;
      const { scrollHeight } = document.body;

      const maybeLoadMore = Math.abs(
        (innerHeight + pageYOffset) - scrollHeight
      ) <= 200;

      if (maybeLoadMore && this.hasMoreData) {
        this.loadMore();
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AlbumTable.scss"
/>
