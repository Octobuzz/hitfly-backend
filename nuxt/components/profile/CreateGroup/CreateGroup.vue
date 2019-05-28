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
            <PencilIcon />
          </template>
        </BaseInput>

        <span class="h2 create-group-description__header_section">
          Описание
        </span>

        <ChooseYear
          v-model="group.year"
          class="create-group-description__year-input"
          title="Год начала карьеры"
        />

        <span class="h3 create-group-description__header_subsection">
          Выберите жанр
        </span>

        <ChooseGenres
          v-model="group.genres"
          class="create-group-description__genre-tag-container"
          dropdown-class="create-group-description__dropdown"
        />

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
import PageHeader from '~/components/shared/PageHeader.vue';
import BaseInput from '~/components/shared/BaseInput.vue';
import BaseTextarea from '~/components/shared/BaseTextarea.vue';
import FormButton from '~/components/shared/FormButton.vue';
import PencilIcon from '~/components/shared/icons/PencilIcon.vue';
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
          input: ''
        },
        year: new Date().getFullYear().toString(),
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
    },

    containerPaddingClass() {
      return this.$store.getters['app-columns/paddingClass'];
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
          careerStartYear: `${this.group.year}-1-1`,
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
        this.$router.push('/profile/my-music');
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
        console.dir(error);
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
