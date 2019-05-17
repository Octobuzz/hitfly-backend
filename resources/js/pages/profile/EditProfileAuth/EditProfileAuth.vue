<template>
  <div class="edit-profile-auth">
    <span class="h2 edit-profile-auth__title">
      Данные авторизации
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
      >
        <template #icon>
          <EnvelopeIcon/>
        </template>
      </BaseInput>

      <FormButton
        v-if="!email.disabled"
        class="edit-profile-auth__save-button"
        modifier="primary"
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
      >
        <template #icon>
          <KeyIcon/>
        </template>
      </BaseInput>

      <FormButton
        v-if="!password.disabled"
        class="edit-profile-auth__save-button"
        modifier="primary"
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

    <BaseLink class="edit-profile-auth__social-networks-link">
      Использовать аккаунт соц.сетей?
    </BaseLink>
  </div>
</template>

<script>
import BaseInput from 'components/BaseInput.vue';
import BaseLink from 'components/BaseLink.vue';
import FormButton from 'components/FormButton.vue';
import EnvelopeIcon from 'components/icons/EnvelopeIcon.vue';
import KeyIcon from 'components/icons/KeyIcon.vue';
import gql from './gql';

export default {
  components: {
    BaseInput,
    BaseLink,
    FormButton,
    EnvelopeIcon,
    KeyIcon
  },
  data() {
    return {
      email: {
        disabled: true,
        button: 'Изменить',
        input: '',
        showError: false,
        errorMessage: ''
      },

      password: {
        disabled: true,
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
    },

    changeEmail() {
      this.email.disabled = !this.email.disabled;
    },

    savePassword() {

    },

    saveEmail() {

    }
  },

  apollo: {
    userProfile() {
      return {
        query: gql.query.MY_PROFILE,
        update({ myProfile }) {
          this.email.input = myProfile.email;
        },
        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<styles
  scoped
  lang="scss"
  src="./EditProfileAuth.scss"
/>
