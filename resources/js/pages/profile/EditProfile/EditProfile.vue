<template>
  <div
    :class="[
      'edit-profile-container',
      containerPaddingClass
    ]"
  >
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
          :show-error="myProfile.name.showError"
          :error-message="myProfile.name.errorMessage"
          @input="myProfile.name.showError = false"
        >
          <template #icon>
            <UserIcon />
          </template>
        </BaseInput>

        <ChooseLocation
          v-model="myProfile.location"
          class="edit-profile-form__location-input"
        />

        <template v-if="isArtist || isStar">
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
            v-model="myProfile.playedGenres.list"
            class="edit-profile-form__choose-genres"
            dropdown-class="edit-profile-form__genre-dropdown"
            :selected-genres-limit="10"
          />

          <span class="h3 edit-profile-form__subsection">
            Описание деятельности
          </span>

          <BaseTextarea
            v-model="myProfile.activity.input"
            class="edit-profile-form__activity-textarea"
            label=""
            :rows="10"
            :show-error="myProfile.activity.showError"
            :error-message="myProfile.activity.errorMessage"
            @input="myProfile.activity.showError = false"
          />
        </template>

        <template v-if="isProfessionalCritic && !isArtist && !isStar">
          <span class="h2 edit-profile-form__section">
            Описание
          </span>

          <span class="h3 edit-profile-form__subsection">
            Описание деятельности
          </span>

          <BaseTextarea
            v-model="myProfile.activity.input"
            class="edit-profile-form__activity-textarea"
            label=""
            :rows="10"
            :show-error="myProfile.activity.showError"
            :error-message="myProfile.activity.errorMessage"
            @input="myProfile.activity.showError = false"
          />
        </template>

        <span class="h2 edit-profile-form__section">
          Любимые жанры
        </span>

        <ChooseGenres
          v-model="myProfile.favouriteGenres.list"
          class="edit-profile-form__choose-genres"
          dropdown-class="edit-profile-form__genre-dropdown"
          :selected-genres-limit="Infinity"
          :none-selected-error="myProfile.favouriteGenres.noneSelectedError"
          @open="myProfile.favouriteGenres.noneSelectedError = false"
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

    <span class="edit-profile__external-auth">
      Использовать аккаунт соц.сетей?
    </span>
    <EditProfileExternalAuth />

    <DeleteAccount />
  </div>
</template>

<script>
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
import EditProfileExternalAuth from '../EditProfileExternalAuth';
import DeleteAccount from '../DeleteAccount';

const MOBILE_WIDTH = 767;

export default {
  components: {
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
    EditProfileAuth,
    EditProfileExternalAuth,
    DeleteAccount
  },

  data() {
    return {
      myProfile: {
        avatar: {
          current: '',
          new: null
        },
        name: {
          input: '',
          showError: false,
          errorMessage: ''
        },
        location: {},
        careerStartYear: new Date().getFullYear().toString(),
        activity: {
          input: '',
          showError: false,
          errorMessage: ''
        },
        email: {
          input: ''
        },
        password: {
          input: ''
        },
        playedGenres: {
          list: []
        },
        favouriteGenres: {
          list: [],
          noneSelectedError: false
        },
        newEmail: null,
        newPassword: null,
        roles: []
      },
      isSaving: false,
      genericProfileAvatarUrl
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    isArtist() {
      return this.myProfile.roles.some(
        role => role.slug === 'performer'
      );
    },

    isStar() {
      return this.myProfile.roles.some(
        role => role.slug === 'star'
      );
    },

    isProfessionalCritic() {
      return this.myProfile.roles.some(
        role => role.slug === 'prof_critic'
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
    }).catch(((error) => {
      this.$message(
        'Ошибка сервера. Не удалось загрузить жанры',
        'info',
        { timeout: 2000 }
      );

      console.dir(error);
    }));
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('loading/setEditProfile', {
      initialized: false
    });
    next();
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setEditProfile', {
        initialized: true,
        success
      });
    },

    onAvatarInput(file) {
      this.myProfile.avatar.new = file;
    },

    validateInput() {
      const showValidationError = () => {
        this.$message(
          'Данные профиля не обновлены. Проверьте правильность введенных данных',
          'info',
          { timeout: 2000 }
        );
      };

      const { name, favouriteGenres } = this.myProfile;
      let hasErrors = false;

      if (name.input === '') {
        name.showError = true;
        name.errorMessage = 'Имя не может быть пустым';
        hasErrors = true;
      }

      if (favouriteGenres.list.length === 0) {
        favouriteGenres.noneSelectedError = true;
        hasErrors = true;
      }

      if (hasErrors) {
        showValidationError();
      }

      return !hasErrors;
    },

    populateValidationErrors(graphQLErrors) {
      const errors = graphQLErrors.map(err => err.validation);
      const { name, activity, favouriteGenres } = this.myProfile;

      errors.forEach((err) => {
        const errKey = Object.keys(err)[0];

        if (errKey.includes('myProfile.genres')) {
          Object.keys(err).forEach((key) => {
            favouriteGenres.showError = true;
            favouriteGenres.errorMessage = 'Введенные адреса почты являются недействительными';
            favouriteGenres.errorMembers.push(
              favouriteGenres.list[+key.split('.')[2]]
            );
          });

          return;
        }

        // eslint-disable-next-line default-case
        switch (errKey) {
          case 'myProfile.username':
            name.showError = true;
            [name.errorMessage] = err[errKey];
            break;

          case 'myProfile.description':
            activity.showError = true;
            [activity.errorMessage] = err[errKey];
            break;
        }
      });
    },

    removeValidationErrors() {
      const { name, activity, favouriteGenres } = this.myProfile;

      name.showError = false;
      activity.showError = false;
      favouriteGenres.showError = false;
    },

    saveProfile() {
      if (this.isSaving) return;

      this.removeValidationErrors();

      if (!this.validateInput()) return;

      this.isSaving = true;

      const profileUpdate = {};
      const {
        avatar,
        name,
        favouriteGenres,
        location,
        activity,
        playedGenres,
        careerStartYear
      } = this.myProfile;

      profileUpdate.username = name.input;
      profileUpdate.genres = favouriteGenres.list
        .map(genre => genre.id);

      if (location.id) {
        profileUpdate.cityId = location.id;
      }

      const artistProfile = {};

      if (this.isArtist || this.isStar) {
        if (activity.input !== '') {
          artistProfile.description = activity.input;
        }

        artistProfile.genres = playedGenres.list
          .map(genre => genre.id);

        if (careerStartYear !== '') {
          artistProfile.careerStart = `${careerStartYear}-1-1`;
        }
      }

      if (this.isProfessionalCritic && activity.input !== '') {
        artistProfile.description = activity.input;
      }

      const mutationVars = { profile: profileUpdate };

      if (this.isArtist) {
        mutationVars.artistProfile = artistProfile;
      }
      if (avatar.new !== null) {
        mutationVars.avatar = avatar.new;
      }

      this.$apollo.mutate({
        mutation: gql.mutation.UPDATE_PROFILE,
        variables: mutationVars,
        update: () => {
          this.isSaving = false;
          this.$router.push('/profile/my-music');
          this.$message(
            'Данные профиля успешно обновлены',
            'info',
            { timeout: 2000 }
          );
        }
      }).catch((error) => {
        this.isSaving = false;

        if (error.message === 'GraphQL error: validation') {
          this.$message(
            'Данные группы не обновлены. Проверьте правильность введенных данных',
            'info',
            { timeout: 2000 }
          );
          this.populateValidationErrors(error.graphQLErrors);
        } else {
          this.$message(
            'На сервере произошла ошибка. Данные группы не обновлены',
            'info',
            { timeout: 2000 }
          );
        }

        console.dir(error);
      });
    }
  },

  apollo: {
    userProfile: {
      query: gql.query.MY_PROFILE,

      fetchPolicy: 'no-cache',

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
        this.myProfile.favouriteGenres.list = favouriteGenres;
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

          this.myProfile.playedGenres.list = genresPlay;
        }

        this.notifyInitialization(true);
      },

      error(err) {
        this.notifyInitialization(false);
        this.$message(
          'На сервере произошла ошибка. Не удалось загрузить данные',
          'info',
          { timeout: 2000 }
        );

        console.dir(err);
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
