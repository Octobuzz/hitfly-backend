<template>
  <div
    :class="[
      'create-group-container',
      containerPaddingClass
    ]"
  >
    <ReturnHeader class="create-group-container__return-header" />

    <PageHeader class="create-group-container__page-header">
      РЕДАКТИРОВАТЬ ПРОФИЛЬ ГРУППЫ
    </PageHeader>

    <div class="create-group">
      <div class="create-group-cover">
        <ChooseAvatar
          ref="avatar"
          :image-url="group.cover.current"
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

        <span class=" h3 create-group-description__header_subsection">
          Описание деятельности
        </span>

        <BaseTextarea
          v-model="group.activity.input"
          class="create-group-description__activity-textarea"
          label="Описание группы"
          :rows="3"
        />

        <span class="create-group-description__header_subsection">
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

        <p
          v-if="group.activeMemberIds.length === 0"
          class="update-group__active-group-members"
        >
          В вашей группе пока нет участников.
        </p>

        <ActiveGroupMembers
          class="update-group__active-group-members"
          :active-member-ids="group.activeMemberIds"
          @remove-member="onRemoveMember"
        />

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
        @press="updateGroup"
      >
        Сохранить изменения
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
import gql from './gql';
import ReturnHeader from '../ReturnHeader.vue';
import ChooseAvatar from '../ChooseAvatar.vue';
import ChooseYear from '../ChooseYear';
import ChooseGenres from '../ChooseGenres';
import SocialMediaLinks from '../SocialMediaLinks';
import ActiveGroupMembers from '../ActiveGroupMembers';
import InviteGroupMembers from '../InviteGroupMembers';

export default {
  components: {
    SpinnerLoader,
    PageHeader,
    ReturnHeader,
    SocialMediaLinks,
    ActiveGroupMembers,
    InviteGroupMembers,
    ChooseAvatar,
    ChooseYear,
    ChooseGenres,
    BaseInput,
    BaseTextarea,
    FormButton,
    PencilIcon
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
        cover: {
          current: '',
          new: null
        },
        name: {
          input: ''
        },
        year: {
          input: new Date().getFullYear().toString()
        },
        activity: {
          input: ''
        },
        genres: [],
        socialLinks: [],
        invitedMembers: [],
        activeMemberIds: []
      },
      dataInitialized: false,
      isSaving: false
    };
  },

  computed: {
    creationQueryGenres() {
      return this.group.genres
        .map(genre => genre.id);
    },
    editGroupId() {
      return this.$store.getters['profile/editGroupId'];
    }
  },

  watch: {
    editGroupId() {
      this.$refs.avatar.clearUserInput();
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('profile/setEditGroupId', { id: null });
    next();
  },

  methods: {
    onCoverInput(file) {
      this.group.cover.new = file;
    },

    onRemoveMember(id) {
      this.$message(
        'Участник будет безвозвратно удален после сохранения изменений',
        'info',
        { timeout: 2000 }
      );

      this.refineActiveMemberIds(id);
    },

    refineActiveMemberIds(unwantedId) {
      this.group.activeMemberIds = this.group.activeMemberIds
        .filter(id => id !== unwantedId);
    },

    updateGroup() {
      if (this.isSaving) return;

      this.isSaving = true;

      this.$apollo.mutate({
        mutation: gql.mutation.UPDATE_MUSIC_GROUP,

        variables: {
          id: this.editGroupId,
          avatar: this.group.cover.new,
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
          activeMemberIds: this.group.activeMemberIds,
          invitedMembers: this.group.invitedMembers
            .map(email => ({
              email
            }))
        }
      }).then(() => {
        this.isSaving = false;
        this.$router.push('/profile/my-music');
        this.$message(
          'Данные группы успешно обновлены',
          'info',
          { timeout: 2000 }
        );
      }).catch((error) => {
        this.isSaving = false;
        this.$message(
          'На сервере произошла ошибка. Данные группы не обновлены',
          'info',
          { timeout: 60000 }
        );
        console.log(error);
      });
    }
  },

  apollo: {
    groupData: {
      query: gql.query.MUSIC_GROUP,

      variables() {
        return {
          id: this.editGroupId
        };
      },

      update({ musicGroup }) {
        const {
          name,
          careerStartYear,
          description,
          genres,
          avatarGroup,
          socialLinks,
          activeMembers
        } = musicGroup;

        this.group.genres = genres;
        this.group.cover.current = avatarGroup
          .filter(image => image.size === 'size_235x235')[0].url;
        this.group.name.input = name;
        this.group.year = new Date(careerStartYear).getFullYear().toString();
        this.group.activity.input = description;
        this.group.socialLinks = socialLinks.map(sl => ({
          network: sl.social_type,
          username: sl.link
        }));
        this.group.activeMemberIds = activeMembers
          .map(user => user.id);

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
  src="./UpdateGroup.scss"
/>
