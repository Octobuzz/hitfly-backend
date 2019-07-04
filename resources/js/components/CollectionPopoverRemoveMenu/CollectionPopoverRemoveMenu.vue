<template>
  <div class="collection-popover-remove-menu">
    <span class="collection-popover-remove-menu__question">
      Вы уверены, что хотите удалить плейлист?
    </span>

    <FormButton
      class="collection-popover-remove-menu__remove-button"
      modifier="secondary"
      loader-size="x-small"
      :is-loading="isRemoving"
      @press="removeCollection"
    >
      Удалить
    </FormButton>

    <FormButton
      class="collection-popover-remove-menu__cancel-button"
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
    collectionId: {
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
    removeCollection() {
      if (this.isRemoving) return;

      this.isRemoving = true;

      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_COLLECTION,
        variables: {
          collectionId: this.collectionId
        }
      })
        .then(() => {
          this.$emit('collection-removed');
        })
        .catch((err) => {
          this.$message(
            'Произошла ошибка. Плейлист не был удален',
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
  src="./CollectionPopoverRemoveMenu.scss"
/>
