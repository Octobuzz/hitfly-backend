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
        >
          <template #icon>
            <PencilIcon/>
          </template>
        </BaseInput>

        <span class="h2 create-group-description__header_section">
          Описание
        </span>

        <BaseInput
          v-model="group.year.input"
          label="Год начала карьеры"
          class="create-group-description__year-input"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </BaseInput>

        <span class="h3 create-group-description__header_subsection">
          Выберите жанр
        </span>

        <ChooseGenres
          v-model="group.genres"
          class="create-group-description__genre-tag-container"
          dropdown-class="create-group-description__dropdown"
        >
          <template #separator>
            <span
              :class="[
                'create-group-description__genre-list-suggestion',
                'create-group-description__regular-text'
              ]"
            >
              или выберите из списка
            </span>
          </template>
        </ChooseGenres>

        <span class="h3 create-group-description__header_subsection">
          Описание деятельности
        </span>

        <BaseTextarea
          v-model="group.activity.input"
          class="create-group-description__activity-textarea"
          label="Описание группы"
          :rows="3"
        />

        <span class="h3 create-group-description__header_subsection">
          Ссылки на соц. сети
        </span>

        <SocialMediaLinks :links.sync="group.socialLinks" />

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

        <InviteGroupMembers v-model="group.invitedMembers" />
      </div>
    </div>
    <div class="create-group-footer">
      <hr class="create-group-footer__delimiter">

      <SpinnerLoader
        v-if="isSaving"
        class="create-group-footer__loader"
      />

      <FormButton
        class="create-group-footer__save-button"
        modifier="primary"
        @press="createGroup"
      >
        Создать группу
      </FormButton>
    </div>
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import PageHeader from 'components/PageHeader.vue';
import BaseInput from 'components/BaseInput.vue';
import BaseTextarea from 'components/BaseTextarea.vue';
import FormButton from 'components/FormButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import CalendarIcon from 'components/icons/CalendarIcon.vue';
import gql from './gql';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import ChooseGenres from '../ChooseGenres';
import SocialMediaLinks from '../SocialMediaLinks';
import InviteGroupMembers from '../InviteGroupMembers';

export default {
  components: {
    SpinnerLoader,
    PageHeader,
    ReturnHeader,
    SocialMediaLinks,
    InviteGroupMembers,
    ChooseAvatar,
    ChooseGenres,
    BaseInput,
    BaseTextarea,
    FormButton,
    PencilIcon,
    CalendarIcon
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      group: {
        cover: null,
        name: {
          input: ''
        },
        year: {
          input: `${new Date().getYear() + 1900}`
        },
        activity: {
          input: ''
        },
        genres: [],
        socialLinks: [],
        invitedMembers: []
      },
      isSaving: false
    };
  },

  computed: {
    creationQueryGenres() {
      return this.group.genres
        .map(genre => genre.id);
    }
  },

  methods: {
    onCoverInput(file) {
      this.group.cover = file;
    },

    createGroup() {
      if (this.isSaving) return;

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
          invitedMembers: this.group.invitedMembers
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
        this.$router.push('/profile');
        this.$message(
          'Группа успешно создана',
          'info',
          { timeout: 2000 }
        );
      }).catch((error) => {
        this.isSaving = false;
        this.$message(
          'На сервере произошла ошибка. Группа не создана',
          'info',
          { timeout: 60000 }
        );
        console.log(error);
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
