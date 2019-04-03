<template>
  <div>
    <div class="create-group-body">
      <div class="group-cover">
        <ChooseAvatar
          v-model="group.cover"
          caption="Загрузить обложку"
        />
      </div>

      <div class="group-description">
        <BaseInput
          v-model="group.name.input"
          label="Название группы"
          class="group-description__name-input"
        >
          <template #icon>
            <PencilIcon/>
          </template>
        </BaseInput>

        <!--TODO: check headers hierarchy-->
        <h2 class="group-description__header_h2">
          Описание
        </h2>

        <BaseInput
          v-model="group.year.input"
          label="Год начала карьеры"
          class="group-description__year-input"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </BaseInput>

        <h3 class="group-description__header_h3">
          Выберите жанр
        </h3>

        <span
          v-if="$apollo.queries.genres.loading"
          class="group-description__genre-tag-container"
        >
          Genres are loading...
        </span>
        <div v-else class="group-description__genre-tag-container">
          <BaseTag
            v-for="(genre, index) in availableGenres"
            :key="index"
            :name="genre"
            :active="group.genres.includes(genre)"
            class="group-description__genre-tag"
            @press="updateGroupGenres($event)"
          />
          <MoreTags @press="incrementGenresOnPage" />
        </div>

        <span class="group-description__genre-list-suggestion group-description__regular-text">
          или выберите из списка
        </span>

        <BaseDropdown
          v-model="group.genres"
          class="group-description__genre-list"
          header="Поиск по жанрам"
          :options="availableGenres"
          :multiple="true"
          :close-on-select="false"
          :searchable="false"
          :max-height="500"
        />

        <h3 class="group-description__header_h3">
          Описание деятельности
        </h3>

        <BaseTextarea
          v-model="group.activity.input"
          class="group-description__activity-textarea"
          label="Описание группы"
        />

        <h3 class="group-description__header_h3">
          Ссылки на соц. сети
        </h3>

        <SocialMediaLinks :links.sync="group.socialLinks" />

        <h2 class="group-description__header_h2 group-description__group-members-header">
          Состав группы
        </h2>

        <span class="group-description__group-members-suggestion group-description__regular-text">
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
import SocialMediaLinks from './SocialMediaLinks.vue';
import InviteGroupMembers from './InviteGroupMembers.vue';
import ChooseAvatar from './ChooseAvatar.vue';
import MoreTags from './MoreTags.vue';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseTextarea from '../../sharedComponents/BaseTextarea.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import BaseTag from '../../sharedComponents/BaseTag.vue';
import FormButton from '../../sharedComponents/FormButton.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';
import CalendarIcon from '../../sharedComponents/icons/CalendarIcon.vue';

export default {
  components: {
    FormButton,
    SocialMediaLinks,
    InviteGroupMembers,
    ChooseAvatar,
    MoreTags,
    BaseInput,
    BaseTextarea,
    BaseDropdown,
    BaseTag,
    PencilIcon,
    CalendarIcon
  },

  data() {
    return {
      group: {
        cover: '',
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
      genres: [],
      genreIdMap: [],
      genresOnPage: 10
    };
  },

  computed: {
    availableGenres() {
      return this.genres.slice(0, this.genresOnPage);
    },
    creationQueryGenres() {
      return this.group.genres
        .map(genre => this.genreIdMap[genre]);
    }
  },

  methods: {
    incrementGenresOnPage() {
      this.genresOnPage += 10;
    },

    updateGroupGenres(tag) {
      if (tag.active) {
        this.group.genres.push(tag.name);
      } else {
        this.group.genres = this.group.genres
          .filter(genre => genre !== tag.name);
      }
    },

    createGroup() {
      this.$apollo.mutate({
        mutation: gql.mutation.CREATE_MUSIC_GROUP,
        variables: {
          avatarGroup: this.group.cover,
          name: this.group.name.input,
          careerStartYear: `${this.group.year.input}-1-1`,
          description: this.group.activity.input,
          genre: this.creationQueryGenres[0], // TODO: remove index after api is fixed
          socialLinks: this.group.socialLinks,
          invitedMembers: this.group.invitedMembers
        },
        update(store, { data: { createMusicGroup } }) {
          const userData = store.readQuery({ query: gql.query.USER });

          userData.user.musicGroups.push(createMusicGroup);
          store.writeQuery({
            query: gql.query.USER,
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
  },

  apollo: {
    genres: {
      query: gql.query.GENRE,
      manual: true,
      result({ data: { genre }, networkStatus }) {
        if (networkStatus === 7) {
          this.genres = genre.map(genre => genre.name);

          // eslint-disable-next-line no-return-assign
          this.genreIdMap = genre.reduce((acc, genreEntry) => {
            acc[genreEntry.name] = genreEntry.id;

            return acc;
          }, {});
        }
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./CreateGroup.scss"
/>
