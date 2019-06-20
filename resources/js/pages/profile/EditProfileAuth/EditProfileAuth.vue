<template>
  <div class="edit-profile-auth">
    <span class="h2 edit-profile-auth__title">
      E-mail
    </span>

    <div
      :class="[
        'edit-profile-auth__input-group',
        {
          'edit-profile-auth__input-group_enabled': !email.disabled
        }
      ]"
    >
      <BaseInput
        v-model="email.input"
        label="E-mail"
        class="edit-profile-auth__email-input"
        :disabled="email.disabled"
        :show-error="email.showError"
        :error-message="email.errorMessage"
        @input="email.showError = false"
      >
        <template #icon>
          <EnvelopeIcon />
        </template>
      </BaseInput>

      <FormButton
        v-if="!email.disabled"
        class="edit-profile-auth__save-button"
        modifier="primary"
        :is-loading="email.isSaving"
        @press="saveEmail"
      >
        Сохранить
      </FormButton>

      <FormButton
        class="edit-profile-auth__change-button"
        modifier="secondary"
        @press="changeEmail"
      >
        {{ emailButton }}
      </FormButton>
    </div>

    <span class="h2 edit-profile-auth__title">
      Пароль
    </span>

    <div
      :class="[
        'edit-profile-auth__input-group',
        {
          'edit-profile-auth__input-group_enabled': !password.disabled
        }
      ]"
    >
      <BaseInput
        v-model="passwordCurrentInput"
        class="edit-profile-auth__password-input"
        :password="true"
        :label="passwordLabel"
        :disabled="password.disabled"
        :show-error="password.current.showError"
        :error-message="password.current.errorMessage"
        @input="removePasswordErrors"
      >
        <template #icon>
          <KeyIcon />
        </template>
      </BaseInput>

      <template v-if="!password.disabled">
        <BaseInput
          v-model="password.new.input"
          class="edit-profile-auth__password-input-new"
          :password="true"
          label="Новый пароль"
          :disabled="password.disabled"
          :show-error="password.new.showError"
          :error-message="password.new.errorMessage"
          @input="removePasswordErrors"
        >
          <template #icon>
            <KeyIcon />
          </template>
        </BaseInput>

        <BaseInput
          v-model="password.confirmatory.input"
          class="edit-profile-auth__password-input-confirmatory"
          :password="true"
          label="Подтвердите пароль"
          :disabled="password.disabled"
          :show-error="password.confirmatory.showError"
          :error-message="password.confirmatory.errorMessage"
          @input="removePasswordErrors"
        >
          <template #icon>
            <KeyIcon />
          </template>
        </BaseInput>
      </template>

      <FormButton
        v-if="!password.disabled"
        class="edit-profile-auth__save-button"
        modifier="primary"
        :is-loading="password.isSaving"
        @press="savePassword"
      >
        Сохранить
      </FormButton>

      <FormButton
        class="edit-profile-auth__change-button"
        modifier="secondary"
        @press="changePassword"
      >
        {{ passwordButton }}
      </FormButton>
    </div>
  </div>
</template>

<script>
import BaseInput from 'components/BaseInput.vue';
import FormButton from 'components/FormButton.vue';
import EnvelopeIcon from 'components/icons/EnvelopeIcon.vue';
import KeyIcon from 'components/icons/KeyIcon.vue';
import gql from './gql';

export default {
  components: {
    BaseInput,
    FormButton,
    EnvelopeIcon,
    KeyIcon
  },
  data() {
    return {
      myProfile: {},

      email: {
        disabled: true,
        isSaving: false,
        button: 'Изменить',
        input: '',
        showError: false,
        errorMessage: ''
      },

      password: {
        disabled: true,
        isSaving: false,
        button: 'Изменить',

        current: {
          input: '',
          showError: false,
          errorMessage: ''
        },

        new: {
          input: '',
          showError: false,
          errorMessage: ''
        },

        confirmatory: {
          input: '',
          showError: false,
          errorMessage: ''
        }
      }
    };
  },

  computed: {
    passwordLabel() {
      return this.password.disabled
        ? 'Пароль'
        : 'Текуший пароль';
    },

    passwordCurrentInput: {
      get() {
        return this.password.disabled
          ? '12345678'
          : this.password.current.input;
      },
      set(val) {
        this.password.current.input = val;
      }
    },

    passwordButton() {
      return this.password.disabled
        ? 'Изменить'
        : 'Отмена';
    },

    emailButton() {
      return this.email.disabled
        ? 'Изменить'
        : 'Отмена';
    },
  },

  methods: {
    changePassword() {
      this.password.disabled = !this.password.disabled;
      this.password.current.input = '';
      this.password.new.input = '';
      this.password.confirmatory.input = '';

      this.removePasswordErrors();
    },

    changeEmail() {
      if (!this.email.enabled) {
        this.email.showError = false;
        this.email.input = this.myProfile.email;
      }

      this.email.disabled = !this.email.disabled;
    },

    removePasswordErrors() {
      this.password.new.showError = false;
      this.password.confirmatory.showError = false;
      this.password.current.showError = false;
    },

    validatePassword() {
      const showValidationError = () => {
        this.$message(
          'Пароль не обновлен. Проверьте правильность введенных данных',
          'info',
          { timeout: 2000 }
        );
      };

      const { new: newPw, confirmatory, current } = this.password;
      let hasErrors = false;

      if (newPw.input === '') {
        newPw.showError = true;
        newPw.errorMessage = 'Пароль не может быть пустым';
        hasErrors = true;
      }

      if (confirmatory.input !== newPw.input) {
        confirmatory.showError = true;
        confirmatory.errorMessage = 'Пароли должны совпадать';
        hasErrors = true;
      }

      if (current.input === '') {
        current.showError = true;
        current.errorMessage = 'Укажите текущий пароль';
        hasErrors = true;
      }

      if (hasErrors) {
        showValidationError();
      }

      return !hasErrors;
    },

    savePassword() {
      if (this.password.isSaving) return;

      const { new: newPw, confirmatory, current } = this.password;

      this.removePasswordErrors();

      if (!this.validatePassword()) return;

      this.password.isSaving = true;

      this.$apollo.mutate({
        mutation: gql.mutation.UPDATE_PASSWORD,
        variables: {
          password: newPw.input,
          confirmPassword: confirmatory.input,
          oldPassword: current.input
        }
      })
        .then(() => {
          this.password.disabled = true;
          newPw.input = '';
          confirmatory.input = '';
          current.input = '';

          this.$message(
            'Пароль успешно изменен',
            'info',
            { timeout: 2000 }
          );
        })
        .catch((err) => {
          if (err.message === 'GraphQL error: Старый пароль неверный') {
            current.showError = true;
            current.errorMessage = 'Указан неверный пароль';
          } else if (err.message === 'GraphQL error: validation') {
            const errors = err.graphQLErrors[0].validation;

            if (errors['password.password']) {
              newPw.showError = true;
              [newPw.errorMessage] = errors['password.password'];
            }

            if (errors['password.confirmPassword']) {
              confirmatory.showError = true;
              [confirmatory.errorMessage] = errors['password.confirmPassword'];
            }

            if (errors['password.oldPassword']) {
              current.showError = true;
              [current.errorMessage] = errors['password.oldPassword'];
            }

            this.$message(
              'Ошибка валидации. Пароль не обновлен',
              'info',
              { timeout: 2000 }
            );
          } else {
            this.$message(
              'Ошибка сервера. Пароль не обновлен',
              'info',
              { timeout: 2000 }
            );

            console.dir(err);
          }
        })
        .then(() => {
          this.password.isSaving = false;
        });
    },

    validateEmail() {
      if (this.email.input === '') {
        this.email.showError = true;
        this.email.errorMessage = 'Поле не может быть пустым';

        return false;
      }

      if (this.email.input === this.myProfile.email) {
        this.email.showError = true;
        this.email.errorMessage = 'Введите новый email';

        return false;
      }

      return true;
    },

    saveEmail() {
      if (this.email.isSaving) return;

      this.email.showError = false;

      if (!this.validateEmail()) return;

      this.email.isSaving = true;

      this.$apollo.mutate({
        mutation: gql.mutation.UPDATE_EMAIL,
        variables: {
          email: this.email.input
        }
      })
        .then(() => {
          this.email.disabled = true;
          this.$message(
            'E-mail успешно изменен',
            'info',
            { timeout: 2000 }
          );
        })
        .catch((err) => {
          if (err.message === 'GraphQL error: validation') {
            this.email.showError = true;
            [this.email.errorMessage] = err.graphQLErrors[0].validation.email;

            this.$message(
              'Ошибка валидации. Email не обновлен',
              'info',
              { timeout: 2000 }
            );
          } else {
            this.$message(
              'Ошибка сервера. Email не обновлен',
              'info',
              { timeout: 2000 }
            );

            console.dir(err);
          }
        })
        .then(() => {
          this.email.isSaving = false;
        });
    }
  },

  apollo: {
    userProfile() {
      return {
        query: gql.query.MY_PROFILE,
        update({ myProfile }) {
          this.myProfile = myProfile;
          this.email.input = myProfile.email;
        },
        error(err) {
          this.$message(
            'Ошибка сервера. Данные авторизации не получены',
            'info',
            { timeout: 2000 }
          );

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
  src="./EditProfileAuth.scss"
/>
