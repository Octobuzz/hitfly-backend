<template>
  <div class="delete-account">
    <div class="delete-account__suggestion">
      <span class="h2 delete-account__suggestion-header">
        Хотите удалить свой аккаунт?
      </span>
      <FormButton
        class="delete-account__suggestion-button"
        modifier="secondary"
        @press="showModal = true"
      >
        Удалить аккаунт
      </FormButton>
    </div>

    <BaseModal
      :open.sync="showModal"
      :auto-close="!isDeleting"
    >
      <div class="delete-account__modal">
        <span class="h2 delete-account__modal-header">
          Вы уверены, что хотите удалить свой аккаунт?
        </span>

        <span class="delete-account__modal-body">
          Все ваши данные: музыка, рекомендации, персональная информация,
          будут храниться еще 30 дней. В течении этого времени вы сможете
          восстановить аккаунт.
        </span>

        <div class="delete-account__modal-button-container">
          <FormButton
            class="delete-account__modal-button"
            modifier="secondary"
            @press="onCancel"
          >
            Отмена
          </FormButton>

          <FormButton
            class="delete-account__modal-button"
            modifier="primary"
            :is-loading="isDeleting"
            @press="deleteAccount"
          >
            Удалить
          </FormButton>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

<script>
import BaseModal from 'components/BaseModal.vue';
import FormButton from 'components/FormButton.vue';
import gql from './gql';

export default {
  components: {
    BaseModal,
    FormButton
  },
  data() {
    return {
      showModal: false,
      isDeleting: false
    };
  },
  methods: {
    deleteAccount() {
      this.isDeleting = true;

      this.$apollo.mutate({
        mutation: gql.mutation.DELETE_MY_PROFILE
      })
        .then(() => {
          window.location.href = '/login';
        })
        .catch((err) => {
          this.isDeleting = false;
          this.$message(
            'При попытке удалиния произошла ошибка. Аккаунт не был удален',
            'info',
            { timeout: 2000 }
          );

          console.dir(err);
        });
    },
    onCancel() {
      if (this.isDeleting) return;

      this.showModal = false;
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./DeleteAccount.scss"
/>
