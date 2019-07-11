<template>
  <div class="track-review">
    <router-link :to="`/user/${reviewerId}/music`">
      <img
        class="track-review__user-avatar"
        :src="reviewerAvatar"
        alt="User avatar"
      >
    </router-link>
    <div class="track-review__comment-section">
      <div class="track-review__comment-section-header">
        <span class="h4 track-review__reviewer">
          {{ reviewer }}
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
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackReview.scss"
/>
