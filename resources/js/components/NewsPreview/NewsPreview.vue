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
      <img
        :key="newsObj.id"
        :src="newsObj.image"
        alt="news cover"
        class="news-preview__cover"
      >
    </div>

    <div
      v-if="!isLoading"
      class="news-preview__footer"
    >
      <router-link class="news-preview__title" :to="link">
        {{ newsObj.title }}
      </router-link>
    </div>
    <router-link class="news-preview__link" :to="link">
      Подробнее
    </router-link>
  </div>
</template>

<script>
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {

  },

  props: {
    newsObj: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      isLoading: false,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30
      }
    };
  },

  computed: {
    link() {
      return `news/${this.newsObj.id}`;
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./NewsPreview.scss"
/>
