<template>
  <div class="album-popover-remove-menu">
    <span class="album-popover-remove-menu__question">
      Вы уверены, что хотите удалить альбом?
    </span>

    <FormButton
      class="album-popover-remove-menu__remove-button"
      modifier="secondary"
      loader-size="x-small"
      :is-loading="isRemoving"
      @press="removeAlbum"
    >
      Удалить
    </FormButton>

    <FormButton
      class="album-popover-remove-menu__cancel-button"
      modifier="secondary"
      loader-size="x-small"
      @press="cancelRemove"
    >
      Отмена
    </FormButton>
  </div>
</template>

<script>
import FormButton from 'components/FormButton.vue';
import gql from './gql';

export default {
  components: {
    FormButton
  },

  props: {
    albumId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      isRemoving: false
    };
  },

  methods: {
    removeAlbum() {
      if (this.isRemoving) return;

      this.isRemoving = true;

      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_ALBUM,
        variables: {
          albumId: this.albumId
        }
      })
        .then(() => {
          this.$emit('album-removed');
        })
        .catch((err) => {
          this.$message(
            'Произошла ошибка. Альбом не был удален',
            'info',
            { timeout: 2000 }
          );

          console.dir(err);
        })
        .then(() => {
          this.isRemoving = false;
        });
    },

    cancelRemove() {
      this.$emit('cancel-remove');
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AlbumPopoverRemoveMenu.scss"
/>
