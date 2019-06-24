<template>
  <TrackReviews
    class="my-reviews"
    for-type="user-track-list"
    for-id="me"
    default-period="week"
  />
</template>

<script>
import TrackReviews from 'components/trackReviewsInterface/TrackReviewsInterface';

export default {
  components: {
    TrackReviews
  }
};
</script>

<style
  scoped
  lang="scss"
>
.my-reviews {
  margin-top: 16px;
}
</style>
