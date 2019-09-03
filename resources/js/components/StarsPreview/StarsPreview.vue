<template>
  <div class="stars-preview">
    <div
      v-if="isLoading"
      class="stars-preview__loader"
    >
      loading expert...
    </div>
    <div
      v-if="!isLoading"
      class="stars-preview__content"
    >
      <img
        :key="star.id"
        :src="star.avatar[1].url"
        alt="stars cover"
        class="stars-preview__cover"
      >
    </div>

    <div
      v-if="!isLoading"
      class="stars-preview__footer"
    >
      <span class="stars-preview__title" v-html="star.username"></span>
      <!-- <span class="stars-preview__footer-description" v-html="star.description"></span> -->
    </div>
  </div>
</template>

<script>
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {

  props: {
    starId: {
      type: Number,
      required: true
    }
  },

  data: () => ({
    isLoading: true
  }),

  apollo: {
    star() {
      return {
        query: gql.query.USER,
        variables() {
          return {
            id: this.starId
          };
        },
        update: ({ user }) => {
          this.isLoading = false;

          return user;
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
  src="./StarsPreview.scss"
/>
