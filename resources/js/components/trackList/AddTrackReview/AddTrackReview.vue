<template>
  <div class="add-track-review">
    <BaseTextArea
      v-model="reviewText"
      class="add-track-review__textarea"
      label="Напишите отзыв на песню"
      :rows="6"
    />
    <FormButton
      class="add-track-review__send-button"
      modifier="secondary"
      loader-size="x-small"
      :is-loading="isSending"
      @press="sendReview"
    >
      Отправить отзыв
    </FormButton>
  </div>
</template>

<script>
import BaseTextArea from 'components/BaseTextarea.vue';
import FormButton from 'components/FormButton.vue';
import gql from './gql';

export default {
  components: {
    BaseTextArea,
    FormButton
  },

  props: {
    trackId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      reviewText: '',
      isSending: false
    };
  },

  methods: {
    sendReview() {
      // review-added event

      const {
        isSending,
        reviewText,
        trackId,
        $apollo
      } = this;

      if (isSending || reviewText === '') return;

      this.isSending = true;

      $apollo.mutate({
        mutation: gql.mutation.ADD_TRACK_COMMENT,
        variables: {
          trackId,
          comment: reviewText
        }
      })
        .then((res) => {
          // attempt to read from cache and write new comment
          // there must be two query: tracks and commentsTrack

          // TODO: implement after list creation

          setTimeout(() => {
            this.reviewText = '';
          }, 300);

          this.$emit('review-added');
        })
        .catch((err) => {
          this.$message(
            'Ошибка сервера. Отзыв не был добавлен',
            'info',
            { timeout: 2000 }
          );
          console.dir(err);
        })
        .then(() => {
          this.isSending = false;
        });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AddTrackReview.scss"
/>
