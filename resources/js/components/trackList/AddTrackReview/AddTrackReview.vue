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
        },
        update: (store, { data: { createComment } }) => {
          ['week', 'month', 'year'].forEach((period) => {
            try {
              const { track: trackWithComments } = store.readQuery({
                query: gql.query.TRACK_WITH_COMMENTS,
                variables: {
                  id: this.trackId,
                  commentedInPeriod: period
                }
              });

              store.writeQuery({
                query: gql.query.TRACK_WITH_COMMENTS,
                variables: {
                  id: this.trackId,
                  commentedInPeriod: period
                },
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

          // TODO: rewrite comments pagination query if exists
        }
      })
        .then((res) => {
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
