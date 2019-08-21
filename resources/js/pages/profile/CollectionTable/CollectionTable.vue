<template>
  <div class="collection-table">
    <CollectionPreview
      v-for="id in collectionIdList"
      :key="id"
      :collection-id="id"
    />
    <p v-if="!collectionIdList.length" class="h3">
      В данном списке нет плейлистов или подборок.
    </p>
  </div>
</template>

<script>
import loadOnScroll from 'mixins/loadOnScroll';
import CollectionPreview from 'components/CollectionPreview';

export default {
  components: {
    CollectionPreview
  },

  mixins: [loadOnScroll],

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

  watch: {
    collectionIdList: {
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
  src="./CollectionTable.scss"
/>
