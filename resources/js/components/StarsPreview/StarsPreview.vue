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
        :key="starsObj.id"
        :src="starsObj.avatar[2].url"
        alt="news cover"
        class="news-preview__cover"
      >
    </div>

    <div
      v-if="!isLoading"
      class="news-preview__footer"
    >
      <span class="news-preview__title" v-html="starsObj.username">
      </span>
    </div>
    <router-link class="news-preview__link" :to="`stars/${starsObj.id}`">
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
    star: {
      type: Number,
      required: true
    }
  },

  data() {
    isLoading: false
  },

  mounted(){
    console.log(this.star);
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./StarsPreview.scss"
/>
