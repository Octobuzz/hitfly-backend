<template>
  <div :class="['review-request-list', containerPaddingClass]">
    <ReviewRequestListEntry
      v-for="{ productId, trackId } in productIdList"
      :key="productId"
      :product-id="productId"
      :track-id="trackId"
      class="review-request-list__entry"
      @track-reviewed="emitRemoveTrackFromRequests({ productId, trackId })"
    />
    <SpinnerLoader
      v-if="isLoading"
      class="review-request-list__loader"
      size="small"
    />
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import ReviewRequestListEntry from 'pages/profile/ReviewRequestListEntry';

export default {
  components: {
    SpinnerLoader,
    ReviewRequestListEntry
  },
  mixins: [containerPaddingClass],
  props: {
    productIdList: {
      type: Array,
      required: true
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    emitRemoveTrackFromRequests(ids) {
      this.$emit('remove-track-from-requests', ids);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./ReviewRequestList.scss"
/>
