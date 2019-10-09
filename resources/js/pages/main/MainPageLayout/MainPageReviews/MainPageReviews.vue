<template>
  <div class="reviewsContainer">
    <ReturnHeader
      class="universal-reviews__return"
    />
    <MainPageStars/>
    <UniversalReviews/>
  </div>
</template>
<script>
import MainPageStars from '../MainPageStars/MainPageStars.vue';
import ReturnHeader from '../ReturnHeader.vue';
import UniversalReviews from '../../../profile/UniversalReviews';

export default {
  data: () => ({
    isLoading: true
  }),
  components: {
    MainPageStars,
    UniversalReviews,
    ReturnHeader
  }
}
</script>
<styles
  lang="scss"
  scoped
  src="./MainPageReviews.scss"
>
</styles>
