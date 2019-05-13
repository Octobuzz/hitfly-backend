<template>
  <div
    :class="[
      'edit-profile-container',
      { 'edit-profile-container_loading': !dataInitialized },
      containerPaddingClass
    ]"
  >
    <SpinnerLoader
      v-if="!dataInitialized"
      class="edit-profile-container__loader"
    />
    <template v-else>
      <ReturnHeader class="edit-profile-container__return-header" />

      <PageHeader class="edit-profile-container__page-header">
        РЕДАКТИРОВАТЬ ПРОФИЛЬ
      </PageHeader>

      <div class="edit-profile">
        <div class="edit-profile-avatar">
          <ChooseAvatar
            :image-url="myProfile.avatar.current || genericProfileAvatarUrl"
            caption="Загрузить фото"
            :circle="true"
            @input="onAvatarInput"
          />
        </div>

        <div class="edit-profile-form">
          <BaseInput
            v-model="myProfile.name.input"
            label="Имя пользователя"
            class="edit-profile-form__name-input"
          >
            <template #icon>
              <UserIcon/>
            </template>
          </BaseInput>

          <ChooseLocation
            v-model="myProfile.location"
            class="edit-profile-form__location-input"
          />

          <span v-if="isArtist" class="h2 edit-profile-form__section">
            Описание
          </span>

          <ChooseYear
            v-if="isArtist"
            v-model="myProfile.careerStartYear"
            class="edit-profile-form__career-start-year-input"
            title="Год начала карьеры"
          />

          <div v-if="isArtist">
            <span class="edit-profile-form__subsection">
              Выберете жанры, в которых играете
            </span>

            <ChooseGenres
              v-model="myProfile.playedGenres"
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

            <span class="edit-profile-form__subsection">
              Описание деятельности
            </span>

            <BaseTextarea
              v-model="myProfile.activity.input"
              class="edit-profile-form__activity-textarea"
              label=""
              :rows="10"
            />
          </div>

          <span class="h2 edit-profile-form__section">
            Вход
          </span>

          <div class="edit-profile-form__input-group">
            <BaseInput
              v-model="myProfile.email.input"
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
              v-model="myProfile.password.input"
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

          <span class="h2 edit-profile-form__section">
            Любимые жанры
          </span>

          <div
            v-if="!genreEditMode"
            class="edit-profile-form__tags-container"
          >
            <BaseTag
              v-for="genre in myProfile.favouriteGenres"
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
            v-model="myProfile.favouriteGenres"
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

        <SpinnerLoader
          v-if="isSaving"
          class="edit-profile-footer__loader"
        />

        <FormButton
          class="edit-profile-footer__save-button"
          modifier="primary"
          @press="saveProfile"
        >
          Сохранить изменения
        </FormButton>
      </div>
    </template>
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import PageHeader from 'components/PageHeader.vue';
import BaseInput from 'components/BaseInput.vue';
import BaseTextarea from 'components/BaseTextarea.vue';
import BaseLink from 'components/BaseLink.vue';
import BaseTag from 'components/BaseTag.vue';
import FormButton from 'components/FormButton.vue';
import UserIcon from 'components/icons/UserIcon.vue';
import EnvelopeIcon from 'components/icons/EnvelopeIcon.vue';
import KeyIcon from 'components/icons/KeyIcon.vue';
import genericProfileAvatarUrl from 'images/generic-user-purple.png';
import gql from './gql';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import ChooseYear from '../ChooseYear';
import ChooseGenres from '../ChooseGenres';
import ChooseLocation from '../ChooseLocation';

export default {
  components: {
    SpinnerLoader,
    PageHeader,
    ReturnHeader,
    ChooseAvatar,
    ChooseYear,
    ChooseGenres,
    ChooseLocation,
    BaseInput,
    BaseTextarea,
    BaseLink,
    BaseTag,
    FormButton,
    UserIcon,
    EnvelopeIcon,
    KeyIcon
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      myProfile: {
        avatar: {
          current: '',
          new: null
        },
        name: {
          input: ''
        },
        location: {},
        careerStartYear: '',
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
        newEmail: null,
        newPassword: null,
        roles: []
      },
      genreEditMode: false,
      dataInitialized: false,
      isSaving: false,
      genericProfileAvatarUrl
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > 767;
    },

    isArtist() {
      return this.myProfile.roles.some(
        role => role === 'Артист'
      );
    }
  },

  mounted() {
    // trigger preloading

    this.$apollo.query({
      query: gql.query.GENRES
    }).catch((error => console.log(error)));
  },

  methods: {
    onAvatarInput(file) {
      this.myProfile.avatar.new = file;
    },

    changeEmail() {
      this.myProfile.newEmail = this.myProfile.email.input;
      this.$message(
        'Нажмите "Сохранить изменения" для обновления',
        'info',
        { timeout: 2000 }
      );
    },

    changePassword() {
      this.myProfile.newPassword = this.myProfile.password.input;
      this.$message(
        'Нажмите "Сохранить изменения" для обновления',
        'info',
        { timeout: 2000 }
      );
    },

    enterGenreEditMode() {
      this.genreEditMode = true;
    },

    saveProfile() {
      if (this.isSaving) return;

      this.isSaving = true;

      const { myProfile } = this;
      const profileUpdate = {};

      if (myProfile.newEmail) {
        profileUpdate.email = myProfile.newEmail;
      }
      if (myProfile.newPassword) {
        profileUpdate.password = myProfile.newPassword;
      }
      profileUpdate.username = myProfile.name.input;
      profileUpdate.genres = myProfile.favouriteGenres
        .map(genre => genre.id);

      if (this.myProfile.location.id) {
        profileUpdate.cityId = this.myProfile.location.id;
      }

      const artistProfile = {};

      if (this.isArtist) {
        if (myProfile.activity.input !== '') {
          artistProfile.description = myProfile.activity.input;
        }

        artistProfile.genres = myProfile.playedGenres
          .map(genre => genre.id);

        if (myProfile.careerStartYear !== '') {
          artistProfile.careerStart = `${myProfile.careerStartYear}-1-1`;
        }
      }

      const mutationVars = { profile: profileUpdate };

      if (this.isArtist) {
        mutationVars.artistProfile = artistProfile;
      }
      if (myProfile.avatar.new !== null) {
        mutationVars.avatar = myProfile.avatar.new;
      }

      this.$apollo.mutate({
        mutation: gql.mutation.UPDATE_PROFILE,
        variables: mutationVars,
        update: (store, { data: { updateMyProfile } }) => {
          this.isSaving = false;
          this.$router.push('/profile/my-music');
          this.$message(
            'Данные профиля успешно обновлены',
            'info',
            { timeout: 2000 }
          );
        },
        error: (err) => {
          this.isSaving = false;
          this.$message(
            'На сервере произошла ошибка. Данные профиля не обновлены',
            'info',
            { timeout: 60000 }
          );
          console.log(err);
        }
      });
    }
  },

  apollo: {
    userProfile: {
      query: gql.query.MY_PROFILE,
      update({ myProfile }) {
        const {
          avatar,
          username,
          location,
          careerStart,
          description,
          email,
          favouriteGenres,
          genresPlay,
          roles
        } = myProfile;

        this.myProfile.avatar.current = avatar
          .filter(image => image.size === 'size_235x235')[0].url;

        this.myProfile.name.input = username;
        this.myProfile.email.input = email;
        this.myProfile.favouriteGenres = favouriteGenres;
        this.myProfile.roles = roles;

        if (location && location.title) {
          this.myProfile.location = location;
        }

        if (roles.some(role => role === 'Артист')) {
          if (careerStart) {
            this.myProfile.careerStartYear = new Date(careerStart)
              .getFullYear().toString();
          }

          if (description) {
            this.myProfile.activity.input = description;
          }

          this.myProfile.playedGenres = genresPlay;
        }

        if (!this.dataInitialized) {
          this.dataInitialized = true;
          this.$emit('data-initialized');
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
