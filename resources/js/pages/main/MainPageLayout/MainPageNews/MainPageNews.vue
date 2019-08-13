<template>
  <UniversalNewsContainer
    query="news"
  >
    <template #default="container">
      <NewsScroller
        class="other-user-music__albums-container"
        :header-class="containerPaddingClass"
        :track-id-list="container.newsIdList"
        :has-more-data="container.hasMoreData"
      >
      </NewsScroller>
    </template>
  </UniversalNewsContainer>
</template>
<script>
  import NewsScroller from 'components/NewsScroller/NewsScroller.vue';
  import UniversalNewsContainer from '../UniversalNewsContainer';

  export default {
    components: {
      NewsScroller,
      UniversalNewsContainer
    },
    data: () => ({

    }),
  }
</script>
