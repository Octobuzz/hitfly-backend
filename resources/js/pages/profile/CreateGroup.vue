<template>
  <div class="create-group-container">
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
import gql from './gql';
import ReturnHeader from './ReturnHeader.vue';
import SocialMediaLinks from './SocialMediaLinks.vue';
import InviteGroupMembers from './InviteGroupMembers.vue';
import ChooseAvatar from './ChooseAvatar.vue';
import ChooseGenres from './ChooseGenres/ChooseGenres.vue';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseTextarea from '../../sharedComponents/BaseTextarea.vue';
import FormButton from '../../sharedComponents/FormButton.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';
import CalendarIcon from '../../sharedComponents/icons/CalendarIcon.vue';

export default {
  components: {
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

  data() {
    return {
      group: {
        cover: {
          current: '',
          new: null
        },
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
      }
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
      this.group.cover.new = file;
    },

    createGroup() {
      this.$apollo.mutate({
        mutation: gql.mutation.CREATE_MUSIC_GROUP,
        variables: {
          avatarGroup: this.group.cover.new,
          name: this.group.name.input,
          careerStartYear: `${this.group.year.input}-1-1`,
          description: this.group.activity.input,
          genre: this.creationQueryGenres,
          // socialLinks: this.group.socialLinks,
          // invitedMembers: this.group.invitedMembers
        },
        update(store, { data: { createMusicGroup } }) {
          const userData = store.readQuery({ query: gql.query.MY_PROFILE });

          userData.myProfile.musicGroups.push(createMusicGroup);
          store.writeQuery({
            query: gql.query.MY_PROFILE,
            data: userData
          });
        }
      }).then((result) => {
        this.$router.push('/profile');
      }).catch(({ graphQLErrors }) => {
        console.log(graphQLErrors);
        // TODO: validation errors
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
