<template>
  <div class="add-track-review">
    <BaseTextArea
      v-model="reviewText"
      class="add-track-review__textarea"
      label="Напишите отзыв на песню"
      :rows="6"
      :show-error="showError"
      :error-message="errorMessage"
      @input="hideError"
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
    productId: {
      type: Number,
      default: null
    },
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
        productId,
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

      const variables = {
        trackId,
        comment: reviewText
      };

      if (this.productId !== null) {
        variables.productId = productId;
      }

      $apollo.mutate({
        mutation: gql.mutation.ADD_TRACK_COMMENT,
        variables,
        update: (store, { data: { createComment } }) => {
          const periods = ['week', 'month', 'year'];

          periods.forEach((period) => {
            try {
              const vars = {
                id: this.trackId,
                commentedInPeriod: period
              };

              const { track: trackWithComments } = store.readQuery({
                query: gql.query.TRACK_WITH_COMMENTS,
                variables: vars
              });

              store.writeQuery({
                query: gql.query.TRACK_WITH_COMMENTS,
                variables: vars,
                data: {
                  track: {
                    ...trackWithComments,
                    comments: [
                      createComment,
                      ...trackWithComments.comments
                    ]
                  }
                }
              });
            } catch (e) {
              // no track or track comments found in the store
            }
          });

          periods.forEach((period) => {
            try {
              // TODO: synchronize variables with original query, add period
              const vars = {
                id: this.trackId,
                pageLimit: 5,
                pageNumber: 1,
                commentPeriod: period
              };

              const { commentsTrack: trackComments } = store.readQuery({
                query: gql.query.TRACK_COMMENTS,
                variables: vars
              });

              store.writeQuery({
                query: gql.query.TRACK_COMMENTS,
                variables: vars,
                data: {
                  commentsTrack: {
                    ...trackComments,
                    data: [
                      createComment,
                      ...trackComments.data
                    ]
                  }
                }
              });
            } catch (e) {
              // no track or track comments found in the store
            }
          });
        }
      })
        .then(() => {
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
  src="./AddTrackReview.scss"
/>
