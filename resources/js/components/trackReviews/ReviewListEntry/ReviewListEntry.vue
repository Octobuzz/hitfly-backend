<template>
  <div class="review-list-entry">
    <div class="review-list-entry__cover-section">

    </div>
  </div>
</template>

<script>
import gql from './gql';

export default {
  props: {
    trackId: {
      type: Number,
      required: true
    },
  },

  data() {
    return {
      track: null
    };
  },

  apollo: {
    track: {
      query: gql.query.TRACK_WITH_COMMENTS,
      update(d) {
        console.log(d);
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./ReviewListEntry.scss"
/>
