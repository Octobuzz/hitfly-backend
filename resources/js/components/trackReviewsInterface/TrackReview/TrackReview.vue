<template>
  <div class="track-review">
    <router-link
      v-if="canRedirect"
      :to="reviewerLink"
    >
      <img
        class="track-review__user-avatar"
        :src="reviewerAvatar"
        alt="User avatar"
      >
    </router-link>
    <img
      v-else
      class="track-review__user-avatar"
      :src="reviewerAvatar"
      alt="User avatar"
    >

    <div class="track-review__comment-section">
      <div class="track-review__comment-section-header">
        <span class="h4 track-review__reviewer">
          <router-link
            v-if="canRedirect"
            :class="[
              'track-review__username',
              'track-review__username_link'
            ]"
            :to="reviewerLink"
          >
            {{ reviewer }}
          </router-link>
          <span v-else class="track-review__username">
            {{ reviewer }}
          </span>
        </span>
        <span class="track-review__date">
          {{ date.slice(0, 10) }}
        </span>
      </div>
      <p class="track-review__comment">
        {{ comment }}
      </p>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';

export default {
  props: {
    reviewerId: {
      type: Number,
      required: true
    },
    reviewer: {
      type: String,
      default: 'Anonymous'
    },
    reviewerRoles: {
      type: Array,
      required: true
    },
    reviewerAvatar: {
      type: String,
      default: anonymousAvatar
    },
    comment: {
      type: String,
      required: true
    },
    date: {
      type: String,
      default: ''
    }
  },

  computed: {
    reviewerLink() {
      if (this.reviewerRoles.includes('star')) {
        return `/user/${this.reviewerId}/user-reviews`;
      }
      return `/user/${this.reviewerId}/music`;
    },

    canRedirect() {
      const { $route, reviewerId } = this;
      const inUserProfile = $route.fullPath.startsWith('/user/');

      return inUserProfile && (+$route.params.userId !== reviewerId);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackReview.scss"
/>
