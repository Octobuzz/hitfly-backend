<template>
  <div
    :class="[
      'create-group-container',
      containerPaddingClass
    ]"
  >
    <ReturnHeader class="create-group-container__return-header" />

    <PageHeader class="create-group-container__page-header">
      СОЗДАТЬ ГРУППУ
    </PageHeader>

    <div class="create-group">
      <div class="create-group-cover">
        <ChooseAvatar
          caption="Загрузить обложку"
          @input="onCoverInput"
        />
      </div>

      <div class="create-group-description">
        <BaseInput
          v-model="group.name.input"
          label="Название группы"
          class="create-group-description__name-input"
          :show-error="group.name.showError"
          :error-message="group.name.errorMessage"
          @input="group.name.showError = false"
        >
          <template #icon>
            <PencilIcon />
          </template>
        </BaseInput>

        <span class="h2 create-group-description__header_section">
          Описание
        </span>

        <ChooseYear
          v-model="group.year.input"
          class="create-group-description__year-input"
          title="Год начала карьеры"
        />

        <span class="h3 create-group-description__header_subsection">
          Выберите жанр
        </span>

        <ChooseGenres
          v-model="group.genres.list"
          class="create-group-description__genre-tag-container"
          dropdown-class="create-group-description__dropdown"
          :selected-genres-limit="5"
          :none-selected-error="group.genres.noneSelectedError"
          @open="group.genres.noneSelectedError = false"
        />

        <span class="h3 create-group-description__header_subsection">
          Описание деятельности
        </span>

        <BaseTextarea
          v-model="group.activity.input"
          class="create-group-description__activity-textarea"
          label="Описание группы"
          :rows="3"
          :show-error="group.activity.showError"
          :error-message="group.activity.errorMessage"
          @input="group.activity.showError = false"
        />

        <span class="h3 create-group-description__header_subsection">
          Ссылки на соц. сети
        </span>

        <span
          :class="[
            'create-group-description__social-links-paragraph',
            'create-group-description__regular-text'
          ]"
        >
          Мы не будем показывать ссылки другим пользователям.
          Они нужны для того, чтобы в случае необходимости мы смогли связаться с Вами.
        </span>

        <SocialMediaLinks :links.sync="group.socialLinks" />

        <!--
        <span
          :class="[
            'h2',
            'create-group-description__header_section',
            'create-group-description__group-members-header'
          ]"
        >
          Состав группы
        </span>

        <span
          :class="[
            'create-group-description__group-members-suggestion',
            'create-group-description__regular-text'
          ]"
        >
          Пригласите других участников группы, указав их email ниже. Им на почту
          придет приглашение со ссылкой на вступление. Вы станете организатором группы.
        </span>

        <InviteGroupMembers
          v-model="group.invitedMembers.list"
          :show-error="group.invitedMembers.showError"
          :error-message="group.invitedMembers.errorMessage"
          :error-members="group.invitedMembers.errorMembers"
        />
        -->
      </div>
    </div>
    <div class="create-group-footer">
      <hr class="create-group-footer__delimiter">

      <FormButton
        class="create-group-footer__save-button"
        modifier="primary"
        :is-loading="isSaving"
        @press="createGroup"
      >
        Создать группу
      </FormButton>
    </div>
  </div>
</template>

<script>
import PageHeader from 'components/PageHeader.vue';
import BaseInput from 'components/BaseInput.vue';
import BaseTextarea from 'components/BaseTextarea.vue';
import FormButton from 'components/FormButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import gql from './gql';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import ChooseYear from '../ChooseYear';
import ChooseGenres from '../ChooseGenres';
import SocialMediaLinks from '../SocialMediaLinks';
import InviteGroupMembers from '../InviteGroupMembers';

export default {
  components: {
    PageHeader,
    ReturnHeader,
    SocialMediaLinks,
    InviteGroupMembers,
    ChooseAvatar,
    ChooseGenres,
    ChooseYear,
    BaseInput,
    BaseTextarea,
    FormButton,
    PencilIcon
  },

  data() {
    return {
      group: {
        cover: null,
        name: {
          input: '',
          showError: false,
          errorMessage: ''
        },
        year: {
          input: new Date().getFullYear().toString(),
          showError: false,
          errorMessage: ''
        },
        activity: {
          input: '',
          showError: false,
          errorMessage: ''
        },
        genres: {
          list: [],
          noneSelectedError: false
        },
        socialLinks: [],
        invitedMembers: {
          list: [],
          showError: false,
          errorMessage: '',
          errorMembers: []
        }
      },
      isSaving: false
    };
  },

  computed: {
    creationQueryGenres() {
      return this.group.genres.list
        .map(genre => genre.id);
    },

    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  },

  methods: {
    onCoverInput(file) {
      this.group.cover = file;
    },

    validateInput() {
      const showValidationError = () => {
        this.$message(
          'Данные группы не обновлены. Проверьте правильность введенных данных',
          'info',
          { timeout: 2000 }
        );
      };

      const { name, activity, genres } = this.group;
      let hasErrors = false;

      if (name.input === '') {
        name.showError = true;
        name.errorMessage = 'Имя не может быть пустым';
        hasErrors = true;
      }

      if (activity.input === '') {
        activity.showError = true;
        activity.errorMessage = 'Описание не может быть пустым';
        hasErrors = true;
      }

      if (genres.list.length === 0) {
        genres.noneSelectedError = true;
        hasErrors = true;
      }

      if (hasErrors) {
        showValidationError();
      }

      return !hasErrors;
    },

    populateValidationErrors(graphQLErrors) {
      const errors = graphQLErrors.map(err => err.validation);
      const { name, activity, invitedMembers } = this.group;

      errors.forEach((err) => {
        const errKey = Object.keys(err)[0];

        if (errKey.includes('musicGroup.invitedMembers')) {
          Object.keys(err).forEach((key) => {
            invitedMembers.showError = true;
            invitedMembers.errorMessage = 'Введенные адреса почты являются недействительными';
            invitedMembers.errorMembers.push(
              invitedMembers.list[+key.split('.')[2]]
            );
          });

          return;
        }

        // eslint-disable-next-line default-case
        switch (errKey) {
          case 'musicGroup.name':
            name.showError = true;
            [name.errorMessage] = err[errKey];
            break;

          case 'musicGroup.description':
            activity.showError = true;
            [activity.errorMessage] = err[errKey];
            break;
        }
      });
    },

    removeValidationErrors() {
      const { name, activity, invitedMembers } = this.group;

      name.showError = false;
      activity.showError = false;
      invitedMembers.showError = false;
      invitedMembers.errorMembers = [];
    },

    createGroup() {
      if (this.isSaving) return;

      this.removeValidationErrors();

      if (!this.validateInput()) return;

      this.isSaving = true;

      this.$apollo.mutate({
        mutation: gql.mutation.CREATE_MUSIC_GROUP,

        variables: {
          avatar: this.group.cover,
          name: this.group.name.input,
          careerStartYear: `${this.group.year.input}-1-1`,
          description: this.group.activity.input,
          genre: this.creationQueryGenres,
          socialLinks: this.group.socialLinks
            .map(sl => ({
              socialType: sl.network,
              link: sl.username
            }))
            .filter(sl => (
              sl.socialType !== '' && sl.link !== ''
            )),
          invitedMembers: this.group.invitedMembers.list
            .map(email => ({
              email
            }))
        },

        update(store, { data: { createMusicGroup: musicGroup } }) {
          try {
            const userData = store.readQuery({ query: gql.query.MY_PROFILE });

            store.writeQuery({
              query: gql.query.MY_PROFILE,
              data: {
                ...userData,
                myProfile: {
                  ...userData.myProfile,
                  musicGroups: [
                    ...userData.myProfile.musicGroups,
                    musicGroup
                  ]
                }
              }
            });
          } catch (error) {
            // we have no MY_PROFILE data to read from cache
          }
        }
      }).then(() => {
        this.isSaving = false;
        this.$router.push('/profile/my-music');
        this.$message(
          'Группа успешно создана',
          'info',
          { timeout: 2000 }
        );
      }).catch((error) => {
        this.isSaving = false;

        if (error.message === 'GraphQL error: validation') {
          this.$message(
            'Группа не создана. Проверьте правильность введенных данных',
            'info',
            { timeout: 2000 }
          );
          this.populateValidationErrors(error.graphQLErrors);
        } else if (error.message === 'GraphQL error: Вы привысили максимальный лимит групп') {
          this.$message(
            'Группа не создана. Вы уже создали максимальное количество групп',
            'info',
            { timeout: 2000 }
          );
        } else {
          this.$message(
            'На сервере произошла ошибка. Группа не создана',
            'info',
            { timeout: 2000 }
          );
        }
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./CreateGroup.scss"
/>
