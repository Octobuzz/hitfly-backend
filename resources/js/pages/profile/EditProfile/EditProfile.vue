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
              <UserIcon />
            </template>
          </BaseInput>

          <ChooseLocation
            v-model="myProfile.location"
            class="edit-profile-form__location-input"
          />

          <template v-if="isArtist">
            <span class="h2 edit-profile-form__section">
              Описание
            </span>

            <ChooseYear
              v-model="myProfile.careerStartYear"
              class="edit-profile-form__career-start-year-input"
              title="Год начала карьеры"
            />

            <span class="h3 edit-profile-form__subsection">
              Выберете жанры, в которых играете
            </span>

            <ChooseGenres
              v-model="myProfile.playedGenres"
              class="edit-profile-form__choose-genres"
              dropdown-class="edit-profile-form__genre-dropdown"
            />

            <span class="h3 edit-profile-form__subsection">
              Описание деятельности
            </span>

            <BaseTextarea
              v-model="myProfile.activity.input"
              class="edit-profile-form__activity-textarea"
              label=""
              :rows="10"
            />
          </template>

          <span class="h2 edit-profile-form__section">
            Любимые жанры
          </span>

          <ChooseGenres
            v-model="myProfile.favouriteGenres"
            class="edit-profile-form__choose-genres"
            dropdown-class="edit-profile-form__genre-dropdown"
          />
        </div>
      </div>

      <div class="edit-profile-footer">
        <hr class="edit-profile-footer__delimiter">

        <FormButton
          class="edit-profile-footer__save-button"
          modifier="primary"
          :is-loading="isSaving"
          @press="saveProfile"
        >
          Сохранить изменения
        </FormButton>
      </div>

      <EditProfileAuth />
    </template>
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import PageHeader from 'components/PageHeader.vue';
import BaseInput from 'components/BaseInput.vue';
import BaseTextarea from 'components/BaseTextarea.vue';
import FormButton from 'components/FormButton.vue';
import UserIcon from 'components/icons/UserIcon.vue';
import genericProfileAvatarUrl from 'images/generic-user-purple.png';
import gql from './gql';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import ChooseYear from '../ChooseYear';
import ChooseGenres from '../ChooseGenres';
import ChooseLocation from '../ChooseLocation';
import EditProfileAuth from '../EditProfileAuth';

export default {
  components: {
    SpinnerLoader,
    BaseInput,
    BaseTextarea,
    FormButton,
    UserIcon,
    PageHeader,
    ReturnHeader,
    ChooseAvatar,
    ChooseYear,
    ChooseGenres,
    ChooseLocation,
    EditProfileAuth
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
        role => role.name === 'Исполнитель'
      );
    },

    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
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

    enterGenreEditMode() {
      this.genreEditMode = true;
    },

    saveProfile() {
      if (this.isSaving) return;

      this.isSaving = true;

      const { myProfile } = this;
      const profileUpdate = {};

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
        update: (store, {data: {updateMyProfile}}) => {
          this.isSaving = false;
          this.$router.push('/profile/my-music');
          this.$message(
            'Данные профиля успешно обновлены',
            'info',
            {timeout: 2000}
          );
        }
      }).catch((err) => {
        this.isSaving = false;
        this.$message(
          'На сервере произошла ошибка. Данные профиля не обновлены',
          'info',
          { timeout: 60000 }
        );
        console.dir(err);
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

        if (roles.some(role => role.name === 'Исполнитель')) {
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
  src="./EditProfile.scss"
/>
