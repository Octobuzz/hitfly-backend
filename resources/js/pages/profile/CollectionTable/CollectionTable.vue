<template>
  <div class="collection-table">
    <CollectionPreview
      v-for="id in collectionIdList"
      :key="id"
      :collection-id="id"
    />
  </div>
</template>

<script>
import CollectionPreview from 'components/CollectionPreview';

export default {
  components: {
    CollectionPreview
  },

  props: {
    collectionIdList: {
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
    collectionIdList: {
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
  src="./CollectionTable.scss"
/>
