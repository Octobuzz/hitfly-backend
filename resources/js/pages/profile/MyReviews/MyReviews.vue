<template>
  <TrackReviews
    class="my-reviews"
    user-id="me"
    default-period="week"
  />
</template>

<script>
import TrackReviews from 'components/trackReviews/TrackReviews';

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
