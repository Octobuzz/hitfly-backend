<template>
  <div
    :class="[
      'create-group-container',
      containerPaddingClass
    ]"
  >
    <ReturnHeader class="create-group-header" />

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

        <!--TODO: check headers hierarchy-->
        <h2 class="create-group-description__header_h2">
          Описание
        </h2>

        <BaseInput
          v-model="group.year.input"
          label="Год начала карьеры"
          class="create-group-description__year-input"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </BaseInput>

        <h3 class="create-group-description__header_h3">
          Выберите жанр
        </h3>

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

        <h3 class="create-group-description__header_h3">
          Описание деятельности
        </h3>

        <BaseTextarea
          v-model="group.activity.input"
          class="create-group-description__activity-textarea"
          label="Описание группы"
          :rows="3"
        />

        <h3 class="create-group-description__header_h3">
          Ссылки на соц. сети
        </h3>

        <SocialMediaLinks :links.sync="group.socialLinks" />

        <h2
          :class="[
            'create-group-description__header_h2',
            'create-group-description__group-members-header'
          ]"
        >
          Состав группы
        </h2>

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
