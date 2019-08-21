<template>
  <div class="album-table">
    <AlbumPreview
      v-for="id in albumIdList"
      :key="id"
      :album-id="id"
    />
    <p v-if="!albumIdList.length" class="h3">
      В данном списке нет альбомов.
    </p>
  </div>
</template>

<script>
import loadOnScroll from 'mixins/loadOnScroll';
import AlbumPreview from 'components/AlbumPreview';

export default {
  components: {
    AlbumPreview
  },

  mixins: [loadOnScroll],

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

  watch: {
    albumIdList: {
      handler() {
        this.$nextTick(() => {
          this.loadOnScroll();
        });
      },
      immediate: true
    }
  },

  methods: {
    loadMore() {
      this.$parent.$emit('load-more');
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AlbumTable.scss"
/>
