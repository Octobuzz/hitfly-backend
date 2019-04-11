<template>
  <div class="edit-profile-container">
    <ReturnHeader class="edit-profile-header" />

    <div class="edit-profile">
      <div class="edit-profile-avatar">
        <ChooseAvatar
          :image-url="avatar.current || '/images/generic-user-purple.png'"
          caption="Загрузить фото"
          :circle="true"
          @input="onAvatarInput"
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

        <h2 class="edit-profile-form__h2">
          Описание
        </h2>

        <BaseInput
          v-model="careerStartYear.input"
          label="Год начала карьеры"
          class="edit-profile-form__career-start-year-input"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </BaseInput>

        <h3 class="edit-profile-form__h3">
          Выберете жанры, в которых играете
        </h3>

        <ChooseGenres
          v-model="playedGenres"
          class="edit-profile-form__choose-genres"
          dropdown-class="edit-profile-form__genre-dropdown"
          :genres-start-count="10"
        >
          <template #separator>
            <span class="edit-profile-form__genre-list-suggestion">
              или выберите из списка
            </span>
          </template>
        </ChooseGenres>

        <h3 class="edit-profile-form__h3">
          Описание деятельности
        </h3>

        <BaseTextarea
          v-model="activity.input"
          class="edit-profile-form__activity-textarea"
          label="Описание группы"
          :rows="10"
        />

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

        <BaseLink class="edit-profile-form__social-networks-link">
          Использовать аккаунт соц.сетей?
        </BaseLink>

        <!--TODO: check headers hierarchy-->
        <h2 class="edit-profile-form__h2">
          Любимые жанры
        </h2>

        <div
          v-if="!genreEditMode"
          class="edit-profile-form__tags-container"
        >
          <BaseTag
            v-for="genre in favouriteGenres"
            :key="genre.id"
            class="edit-profile-form__chosen-tag"
            :name="genre.name"
            :active="true"
          />
        </div>
        <FormButton
          v-if="!genreEditMode"
          :class="[
            'edit-profile-form__change-button',
            'edit-profile-form__favourite-genres-button'
          ]"
          modifier="secondary"
          @press="enterGenreEditMode"
        >
          Изменить
        </FormButton>

        <ChooseGenres
          v-else
          v-model="favouriteGenres"
          class="edit-profile-form__choose-genres"
          dropdown-class="edit-profile-form__genre-dropdown"
          :genres-start-count="20"
        >
          <template #separator>
            <span class="edit-profile-form__genre-list-suggestion">
              или выберите из списка
            </span>
          </template>
        </ChooseGenres>
      </div>
    </div>

    <div class="edit-profile-footer">
      <hr class="edit-profile-footer__delimiter">

      <FormButton
        class="edit-profile-footer__save-button"
        modifier="primary"
        @press="saveProfile"
      >
        Сохранить изменения
      </FormButton>
    </div>
  </div>
</template>

<script>
import gql from './gql';
import ChooseAvatar from './ChooseAvatar.vue';
import ChooseGenres from './ChooseGenres.vue';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseTextarea from '../../sharedComponents/BaseTextarea.vue';
import BaseLink from '../../sharedComponents/BaseLink.vue';
import BaseTag from '../../sharedComponents/BaseTag.vue';
import FormButton from '../../sharedComponents/FormButton.vue';
import UserIcon from '../../sharedComponents/icons/UserIcon.vue';
import BalloonIcon from '../../sharedComponents/icons/BalloonIcon.vue';
import CalendarIcon from '../../sharedComponents/icons/CalendarIcon.vue';
import EnvelopeIcon from '../../sharedComponents/icons/EnvelopeIcon.vue';
import KeyIcon from '../../sharedComponents/icons/KeyIcon.vue';
import ReturnHeader from './ReturnHeader.vue';

export default {
  components: {
    ReturnHeader,
    ChooseAvatar,
    ChooseGenres,
    BaseInput,
    BaseTextarea,
    BaseLink,
    BaseTag,
    FormButton,
    UserIcon,
    BalloonIcon,
    CalendarIcon,
    EnvelopeIcon,
    KeyIcon
  },
  data() {
    return {
      avatar: {
        current: '',
        new: null
      },
      name: {
        input: ''
      },
      location: {
        input: ''
      },
      careerStartYear: {
        input: ''
      },
      activity: {
        input: ''
      },
      email: {
        input: ''
      },
      password: {
        input: ''
      },
      playedGenres: [],
      favouriteGenres: [],
      genreEditMode: false
    };
  },
  methods: {
    onAvatarInput(file) {
      this.avatar.new = file;
    },
    changeEmail() {
      console.log('change email');
    },
    changePassword() {
      console.log('change password');
    },
    enterGenreEditMode() {
      this.genreEditMode = true;
    },
    saveProfile() {
      console.log('profile saved');
    }
  },
  apollo: {
    favouriteGenres: {
      query: gql.query.GENRE_LIST,
      manual: true,
      result({ data: { genre }, networkStatus }) {
        if (networkStatus === 7) {
          this.favouriteGenres = genre
            .filter(genreEntry => genreEntry.userFavourite);
        }
      },
      error(err) {
        console.log(err);
      }
    },

    myProfile: {
      query: gql.query.MY_PROFILE,
      manual: true,
      result({ data: { myProfile }, networkStatus }) {
        if (networkStatus === 7) {
          console.log(myProfile);

          const {
            username,
            location,
            dateRegister,
            email,
            avatar
          } = myProfile;

          this.name.input = username;
          this.location.input = location.title;
          this.careerStartYear.input = new Date(dateRegister).getFullYear();
          this.email.input = email;
          this.avatar.current = avatar;
        }
      },
      error(err) {
        console.log(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./EditUser.scss"
/>
