<template>
  <div class="track-review-request-form">
    <BaseTextArea
      v-model="reviewText"
      class="track-review-request-form__textarea"
      label="Напишите отзыв на песню"
      :rows="4"
      :show-error="showError"
      :error-message="errorMessage"
      @input="hideError"
    />
    <FormButton
      class="track-review-request-form__send-button"
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
      isSending: false,
      showError: false,
      errorMessage: ''
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

      if (isSending) return;

      if (reviewText === '') {
        this.showError = true;
        this.errorMessage = 'Заполните поле отзыва';

        return;
      }
      if (reviewText.length >= 250) {
        this.showError = true;
        this.errorMessage = 'Длина отзыва должна быть не больше 250 символов';

        return;
      }

      this.isSending = true;

      $apollo.mutate({
        mutation: gql.mutation.ADD_TRACK_COMMENT,
        variables: {
          trackId,
          comment: reviewText
        }
      })
        .then(() => {
          this.$message(
            'Отзыв добавлен',
            'info',
            { timeout: 2000 }
          );
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
    },

    hideError() {
      if (this.showError) {
        this.showError = false;
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackReviewRequestForm.scss"
/>
