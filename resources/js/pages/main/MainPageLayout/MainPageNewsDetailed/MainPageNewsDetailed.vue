<template>
  <section class="detailedNews">
    <SpinnerLoader v-if="isLoading" />
    <div class="detailedNews__body" v-else>
      <ReturnHeader />
      <div v-html="newsItem.content"></div>
    </div>
  </section>
</template>
<script>
  import SpinnerLoader from 'components/SpinnerLoader.vue';
  import ReturnHeader from '../ReturnHeader.vue';
  import gql from './gql';

  export default {
    data: () => ({
      newsItem: null
    }),
    components: {
      ReturnHeader,
      SpinnerLoader
    },
    computed: {
      newsId() {
        const { newsId } = this.$route.params;
        return newsId;
      },
      isLoading() {
        if(this.newsItem === null){
          return true;
        } else {
          return false;
        }
      }
    },
    apollo: {
      newsItem() {
        return {
          client: this.apolloClient,
          query: gql.query.GET_NEWS_ITEM,
          variables: {
            id: this.newsId
          },
          update(data) {
            return data.newsOne;
          }
        }
      }
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./MainPageNewsDetailed.scss"
/>
