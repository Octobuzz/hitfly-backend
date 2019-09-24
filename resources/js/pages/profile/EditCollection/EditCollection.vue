<template>
  <div :class="['edit-collection', containerPaddingClass]">
    <ReturnHeader class="edit-collection__return-header" />

    <SpinnerLoader
      v-if="collectionIsFetching"
      class="edit-collection__loader"
    />

    <template v-else>
      <PageHeader class="edit-collection__page-header">
        РЕДАКТИРОВАНИЕ ПЛЕЙЛИСТА
      </PageHeader>

      <div class="edit-collection__form-body">
        <ChooseAvatar
          class="edit-collection__image-input"
          :image-url="collectionImage"
          caption="Загрузить обложку"
          @input="onImageInput"
        />

        <BaseInput
          v-model="collection.title"
          label="Название плейлиста"
          class="edit-collection__title-input"
          :error-message="titleErrorMessage"
          :show-error="showTitleError"
          @input="hideTitleError"
        >
          <template #icon>
            <PencilIcon />
          </template>
        </BaseInput>
      </div>

      <div class="edit-collection__form-footer">
        <hr class="edit-collection__delimiter">

        <FormButton
          class="edit-collection__save-button"
          modifier="primary"
          :is-loading="isUpdating"
          @press="updateCollection"
        >
          Сохранить изменения
        </FormButton>
      </div>
    </template>
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import PencilIcon from 'components/icons/PencilIcon.vue';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import BaseInput from 'components/BaseInput.vue';
import FormButton from 'components/FormButton.vue';
import PageHeader from 'components/PageHeader.vue';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import gql from './gql';

export default {
  components: {
    PencilIcon,
    SpinnerLoader,
    BaseInput,
    FormButton,
    PageHeader,
    ReturnHeader,
    ChooseAvatar
  },

  mixins: [containerPaddingClass],

  data() {
    return {
      collection: null,
      imageFile: null,
      isUpdating: false,
      titleErrorMessage: '',
      showTitleError: false
    };
  },

  computed: {
    collectionImage() {
      if (this.collection && this.collection.image) {
        return this.collection.image;
      }
      return '';
    },

    collectionIsFetching() {
      return this.$apollo.loading;
    }
  },

  methods: {
    onImageInput(file) {
      this.imageFile = file;
    },

    goToNotFound() {
      this.$store.commit('appColumns/set404', true);
    },

    validateInput() {
      if (!this.collection.title.length) {
        this.titleErrorMessage = 'Имя плейлиста не может быть пустым';
        this.showTitleError = true;

        return false;
      }
      return true;
    },

    hideTitleError() {
      this.showTitleError = false;
    },

    updateCollection() {
      if (!this.validateInput()) return;

      this.isUpdating = true;

      const variables = {
        id: this.collection.id,
        name: this.collection.title
      };

      if (this.imageFile) {
        variables.image = this.imageFile;
      }

      this.$apollo.provider.clients.private.mutate({
        mutation: gql.mutation.UPDATE_COLLECTION,
        variables
      })
        .then(() => {
          this.$message(
            'Плейлист обновлен',
            'info',
            { timeout: 2000 }
          );

          this.$router.push('/profile/my-music');
        })
        .catch((err) => {
          console.dir(err);

          if (err.message === 'GraphQL error: validation') {
            if (err.graphQLErrors[0].validation.name instanceof Array) {
              [this.titleErrorMessage] = err.graphQLErrors[0].validation.name;
              this.showTitleError = true;
            }

            return;
          }

          this.$message(
            'Обновление не удалось. Попробуйте позже или свяжитесь с администратором',
            'info',
            { timeout: 2000 }
          );
        })
        .then(() => {
          this.isUpdating = false;
        });
    }
  },

  apollo: {
    collection() {
      return {
        client: 'private',
        query: gql.query.COLLECTION,
        variables: {
          id: this.$route.params.collectionId
        },
        update({ collection }) {
          if (!collection) {
            this.goToNotFound();

            return;
          }

          const image = collection.image.find(
            img => img.size === 'size_235x235'
          ).url;
          const { id, title } = collection;

          // eslint-disable-next-line consistent-return
          return {
            id,
            title,
            image
          };
        },
        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./EditCollection.scss"
/>
