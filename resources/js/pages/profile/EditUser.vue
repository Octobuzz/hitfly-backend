<template>
  <div class="edit-profile">
    <div class="edit-profile-avatar">
      <ChooseAvatar
        v-model="avatar"
        caption="Загрузить фото"
        :circle="true"
      />
    </div>

    <div class="edit-profile-form">
      <BaseInput
        v-model="name.input"
        label="Имя пользователя"
        class="edit-profile-form__name-input"
      >
        <template #icon>
          <UserIcon/>
        </template>
      </BaseInput>

      <BaseInput
        v-model="location.input"
        label="Город"
        class="edit-profile-form__location-input"
      >
        <template #icon>
          <BalloonIcon/>
        </template>
      </BaseInput>

      <!--TODO: check headers hierarchy-->
      <h2 class="edit-profile-form__h2">
        Вход
      </h2>

      <div class="edit-profile-form__input-group">
        <BaseInput
          v-model="email.input"
          label="E-mail"
          class="edit-profile-form__email-input"
        >
          <template #icon>
            <EnvelopeIcon/>
          </template>
        </BaseInput>

        <FormButton
          class="edit-profile-form__change-button"
          modifier="secondary"
          @press="changeEmail"
        >
          Изменить
        </FormButton>
      </div>

      <div class="edit-profile-form__input-group">
        <BaseInput
          v-model="password.input"
          label="Пароль"
          class="edit-profile-form__password-input"
          :password="true"
        >
          <template #icon>
            <KeyIcon/>
          </template>
        </BaseInput>

        <FormButton
          class="edit-profile-form__change-button"
          modifier="secondary"
          @press="changePassword"
        >
          Изменить
        </FormButton>
      </div>

      <BaseLink class="social-networks-link">
        Использовать аккаунт соц.сетей?
      </BaseLink>

      <ChooseGenres v-model="selectedGenres" />
    </div>
  </div>
</template>

<script>
import ChooseAvatar from './ChooseAvatar.vue';
import ChooseGenres from './ChooseGenres.vue';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseLink from '../../sharedComponents/BaseLink.vue';
import FormButton from '../../sharedComponents/FormButton.vue';
import UserIcon from '../../sharedComponents/icons/UserIcon.vue';
import BalloonIcon from '../../sharedComponents/icons/BalloonIcon.vue';
import EnvelopeIcon from '../../sharedComponents/icons/EnvelopeIcon.vue';
import KeyIcon from '../../sharedComponents/icons/KeyIcon.vue';

export default {
  components: {
    ChooseAvatar,
    ChooseGenres,
    BaseInput,
    BaseLink,
    FormButton,
    UserIcon,
    BalloonIcon,
    EnvelopeIcon,
    KeyIcon
  },
  data() {
    return {
      avatar: '/images/generic-user-purple.png',
      name: {
        input: ''
      },
      location: {
        input: ''
      },
      email: {
        input: ''
      },
      password: {
        input: ''
      },
      selectedGenres: []
    };
  },
  methods: {
    changeEmail() {
      console.log('change email');
    },
    changePassword() {
      console.log('change password');
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./EditUser.scss"
/>
