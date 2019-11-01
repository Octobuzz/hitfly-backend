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
      <router-link
        class="news-preview__backdrop"
        :to="link"
      />
      <img
        :key="newsObj.id"
        :src="newsObj.image"
        alt="news cover"
        class="news-preview__cover"
      >

      <!--      Hide title until it is necessary-->
      <!--      <div class="news-preview__footer">-->
      <!--        <router-link class="news-preview__title" :to="link">-->
      <!--          {{ newsObj.title }}-->
      <!--        </router-link>-->
      <!--      </div>-->

      <router-link class="news-preview__link" :to="`news/${newsObj.id}`">
        Подробнее
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
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
