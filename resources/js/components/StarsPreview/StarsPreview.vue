<template>
  <div class="stars-preview">
    <div
      v-if="isLoading"
      class="stars-preview__loader"
    >
      loading expert...
    </div>
    <router-link
    :to="`/user/${starId}/user-reviews`"
      v-if="!isLoading"
      class="stars-preview__content"
    >
      <img
        :key="star.id"
        :src="star.avatar[1].url"
        alt="stars cover"
        class="stars-preview__cover"
      >
    </router-link>

    <div
      v-if="!isLoading"
      class="stars-preview__footer"
    >
      <router-link
      :to="`/user/${starId}/user-reviews`"
      class="stars-preview__title"
      v-html="star.username"
      ></router-link>
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
