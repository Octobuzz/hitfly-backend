<template>
  <div :class="['universal-reviews', containerPaddingClass]">
    <ReturnHeader
      v-if="renderHeader"
      class="universal-reviews__return"
    />
    <TrackReviewsInterface
      v-if="created"
      class="universal-reviews"
      :for-type="forType"
      :for-id="forId"
      default-period="week"
    />
  </div>
</template>

<script>
import currentPath from 'mixins/currentPath';
import TrackReviewsInterface from 'components/trackReviewsInterface/TrackReviewsInterface';
import ReturnHeader from '../ReturnHeader.vue';

/* eslint-disable no-param-reassign */

const getProps = (vm) => {
  // eslint-disable-next-line no-shadow
  const { currentPath, $route: { params } } = vm;

  switch (currentPath) {
    case '/profile/reviews':
      return {
        forType: 'user-track-list',
        forId: 'me'
      };

    case '/user/:userId/reviews':
      return {
        forType: 'user-track-list',
        forId: +params.userId
      };

    case '/profile/reviews/:trackId':
      return {
        forType: 'track',
        forId: +params.trackId
      };

    case '/user/:userId/reviews/:trackId':
      return {
        forType: 'track',
        forId: +params.trackId
      };

    default:
      return {};
  }
};

/* eslint-enable no-param-reassign */

export default {
  components: {
    TrackReviewsInterface,
    ReturnHeader
  },

  mixins: [currentPath],

  data() {
    return {
      forType: null,
      forId: null,
      created: false
    };
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },
    renderHeader() {
      switch (this.currentPath) {
        case '/profile/reviews':
        case '/user/:userId/reviews':
          return false;

        default:
          return true;
      }
    }
  },

  created() {
    const { forType, forId } = getProps(this);

    this.forType = forType;
    this.forId = forId;
    this.created = true;
  },

  beforeUpdate() {
    const { forType, forId } = getProps(this);

    this.forType = forType;
    this.forId = forId;
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UniversalReviews.scss"
/>
