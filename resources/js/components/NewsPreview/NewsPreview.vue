<template>
  <div class="news-preview">
    <div
      v-if="isLoading"
      class="news-preview__loader"
    >
      loading news...
    </div>
    <div
      v-if="!isLoading"
      class="news-preview__content"
    >
      <div class="news-preview__drape" />

      <img
        :key="newsId"
        :src="newsCoverUrl"
        alt="news cover"
        class="news-preview__cover"
      >
    </div>

    <div
      v-if="!isLoading"
      class="news-preview__footer"
    >
      <router-link :to="titleLink">
        <span class="news-preview__title">
          {{ news.title }}
        </span>
      </router-link>
      <span class="news-preview__author">
        {{ news.author }}
      </span>
    </div>
  </div>
</template>

<script>
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {

  },

  props: {
    newsId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      isLoading: true,
      news: null
    };
  },

  computed: {
    newsCoverUrl() {
      if (this.windowWidth <= MOBILE_WIDTH) {
        return this.news.cover
          .filter(cover => cover.size === 'size_104x104')[0].url;
      }

      return this.news.cover
        .filter(cover => cover.size === 'size_120x120')[0].url;
    },
  },

  methods: {

  },

  apollo: {
    news() {
      return {
        query: gql.query.NEWS,
        variables() {
          // use function to allow rendering another news when the prop changes

          return {
            id: this.newsId
          };
        },
        update: ({ news }) => {
          console.log(news);
          this.isLoading = false;

          return news;
        },
        error: (error) => {
          console.log(error);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./newsPreview.scss"
/>
