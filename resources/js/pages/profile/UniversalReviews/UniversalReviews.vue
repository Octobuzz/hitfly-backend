<template>
  <div class="universal-reviews">
    <ReturnHeader v-if="renderHeader" :class="containerPaddingClass" />
    <TrackReviews
      class="universal-reviews"
      :for-type="forType"
      :for-id="forId"
      default-period="week"
    />
  </div>
</template>

<script>
import TrackReviews from 'components/trackReviewsInterface/TrackReviewsInterface';
import ReturnHeader from '../ReturnHeader.vue';

/* eslint-disable no-param-reassign */

const updateProps = (vm) => {
  const { fullPath, params } = vm.$route;

  if (fullPath === '/profile/reviews') {
    vm.forType = 'user-track-list';
    vm.forId = 'me';
  }

  if (params.trackId) {
    vm.forType = 'track';
    vm.forId = +params.trackId;
  }
};

/* eslint-enable no-param-reassign */

export default {
  components: {
    TrackReviews,
    ReturnHeader
  },
  data() {
    return {
      forType: null,
      forId: null
    };
  },
  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },
    renderHeader() {
      return this.$route.fullPath !== '/profile/reviews';
    }
  },
  created() {
    updateProps(this);
  },
  beforeUpdate() {
    updateProps(this);
  }
};
</script>

<style
  scoped
  lang="scss"
>
.universal-reviews {
  margin-top: 16px;
}
</style>
