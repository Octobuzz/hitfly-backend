<template>
  <div class="create-group">
    <div class="group-cover">
      <button class="group-cover-download">
        Загрузить обложку
      </button>
    </div>

    <div class="group-description">
      <div class="group-description__name-input">
        <BaseInput
          v-model="group.name.input"
          label="Название группы"
          :show-error="group.name.showError"
          :error-message="group.name.error"
        >
          <template v-slot:icon>
            <PencilIcon/>
          </template>
        </BaseInput>
      </div>

      <!--TODO: check headers hierarchy-->
      <h2 class="group-description__header_h2">
        Описание
      </h2>

      <div class="group-description__year-input">
        <BaseInput
          v-model="group.year.input"
          label="Год начала карьеры"
          :show-error="group.year.showError"
          :error-message="group.year.errorMessage"
        >
          <template v-slot:icon>
            <CalendarIcon/>
          </template>
        </BaseInput>
      </div>

      <h3 class="group-description__header_h3">
        Выберите жанр
      </h3>

      <div class="group-description__genre-tags">
        <BaseTag
          v-for="(genre, index) in availableGenres"
          :key="index"
          :name="genre"
          :active="group.genres.includes(genre)"
          @press="onTagPress($event)"
        />
      </div>

      <span class="group-description__genre-list-suggestion group-description__regular-text">
        или выберите из списка
      </span>

      <div class="group-description__genre-list">
        <BaseDropdown
          v-model="group.genres"
          header="Поиск по жанрам"
          :options="availableGenres"
          :multiple="true"
          :close-on-select="false"
          :searchable="false"
          :max-height="500"
        />
      </div>

      <h3 class="group-description__header_h3">
        Описание деятельности
      </h3>

      <div class="group-description__activity-textarea">
        <BaseTextarea
          v-model="group.activity.textarea"
          label="Описание группы"
          :show-error="group.activity.showError"
          :error-message="group.activity.error"
        />
      </div>

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
</template>

<script>
import SocialMediaLinks from './SocialMediaLinks.vue';
import InviteGroupMembers from './InviteGroupMembers.vue';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseTextarea from '../../sharedComponents/BaseTextarea.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import BaseTag from '../../sharedComponents/BaseTag.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';
import CalendarIcon from '../../sharedComponents/icons/CalendarIcon.vue';

export default {
  components: {
    SocialMediaLinks,
    InviteGroupMembers,
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
        name: {
          input: '',
          errorMessage: '',
          showError: false
        },
        year: {
          input: `${new Date().getYear() + 1900}`,
          errorMessage: '',
          showError: false
        },
        activity: {
          input: '',
          errorMessage: '',
          showError: false
        },
        genres: [],
        socialLinks: [
          { username: 'mikki', network: 'facebook' },
          { username: 'mikuo', network: 'facebook' }
        ],
        invitedMembers: []
      },
      availableGenres: [
        'Классика',
        'Рок',
        'Альтернатива',
        'Джаз',
        'Поп',
        'Классика1',
        'Рок1',
        'Альтернатива1',
        'Классика2',
        'Альтернатива3',
        'Джаз3',
        'Поп3',
      ],
    };
  },

  methods: {
    onTagPress(tag) {
      if (tag.active) {
        this.group.genres.push(tag.name);
      } else {
        this.group.genres = this.group.genres
          .filter(genre => genre !== tag.name);
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
