<template>
  <div v-show="dataInitialized" class="edit-profile-container">
    <ReturnHeader/>

    <div class="edit-profile">
      <div class="edit-profile-avatar">
        <ChooseAvatar
          :image-url="avatar.current || genericProfileAvatarUrl"
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

        <h2 v-if="isArtist" class="edit-profile-form__h2">
          Описание
        </h2>

        <BaseInput
          v-if="isArtist"
          v-model="careerStartYear.input"
          label="Год начала карьеры"
          class="edit-profile-form__career-start-year-input"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </BaseInput>


        <div v-if="isArtist">
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
            label=""
            :rows="10"
          />
        </div>

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
import BaseInput from 'components/BaseInput.vue';
import BaseTextarea from 'components/BaseTextarea.vue';
import BaseLink from 'components/BaseLink.vue';
import BaseTag from 'components/BaseTag.vue';
import FormButton from 'components/FormButton.vue';
import UserIcon from 'components/icons/UserIcon.vue';
import BalloonIcon from 'components/icons/BalloonIcon.vue';
import CalendarIcon from 'components/icons/CalendarIcon.vue';
import EnvelopeIcon from 'components/icons/EnvelopeIcon.vue';
import KeyIcon from 'components/icons/KeyIcon.vue';
import genericProfileAvatarUrl from 'images/generic-user-purple.png';
import gql from './gql';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import ChooseGenres from '../ChooseGenres';

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
      newEmail: null,
      newPassword: null,
      genreEditMode: false,
      roles: [],
      dataInitialized: false,
      genericProfileAvatarUrl
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > 767;
    },

    isArtist() {
      return this.roles.some(
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
      this.avatar.new = file;
    },

    changeEmail() {
      this.newEmail = this.email.input;
      this.$message(
        'Нажмите "Сохранить изменения" для обновления',
        'info',
        { timeout: 2000 }
      );
    },

    changePassword() {
      this.$message(
        'Нажмите "Сохранить изменения" для обновления',
        'info',
        { timeout: 2000 }
      );
      this.newPassword = this.password.input;
    },

    enterGenreEditMode() {
      this.genreEditMode = true;
    },

    saveProfile() {
      const profile = {};

      if (this.newEmail) {
        profile.email = this.newEmail;
      }
      if (this.newPassword) {
        profile.password = this.newPassword;
      }
      profile.username = this.name.input;
      profile.genres = this.favouriteGenres
        .map(genre => genre.id);

      // TODO: city id

      const artistProfile = {};

      if (this.isArtist) {
        if (this.activity.input !== '') {
          artistProfile.description = this.activity.input;
        }

        artistProfile.genres = this.playedGenres
          .map(genre => genre.id);

        if (this.careerStartYear.input !== '') {
          artistProfile.careerStart = `${this.careerStartYear.input}-1-1`;
        }
      }

      const mutationVars = { profile };

      if (this.isArtist) {
        mutationVars.artistProfile = artistProfile;
      }
      if (this.avatar.new !== null) {
        mutationVars.avatar = this.avatar.new;
      }

      this.$apollo.mutate({
        mutation: gql.mutation.UPDATE_PROFILE,
        variables: mutationVars,
        update: (store, { data: { updateMyProfile } }) => {
          this.$router.push('/profile');
        },
        error(err) {
          console.log(err);
        }
      });
    }
  },

  apollo: {
    myProfile: {
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

        this.avatar.current = avatar
          .filter(image => image.size === 'size_235x235')[0].url;

        this.name.input = username;
        this.email.input = email;
        this.favouriteGenres = favouriteGenres;
        this.roles = roles;

        if (location && location.title) {
          this.location.input = location.title;
        }

        if (roles.some(role => role === 'Артист')) {
          if (careerStart) {
            this.careerStartYear.input = `${
              new Date(careerStart).getFullYear()
            }`;
          }

          if (description) {
            this.activity.input = description;
          }

          this.playedGenres = genresPlay;
        }
      },
      result() {
        this.dataInitialized = true;
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
