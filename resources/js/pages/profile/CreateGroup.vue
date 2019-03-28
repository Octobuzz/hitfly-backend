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

      <span class="group-description__genre-list-suggestion">
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

      <SocialMediaLinks
        :links="[{ contact: 'aaa', network: 'facebook' }]"
      />

      <!--<BaseInput-->
        <!--v-model="inputVal"-->
        <!--label="input-label"-->
        <!--:show-error="true"-->
        <!--error-message="error message"-->
      <!--&gt;-->
        <!--<template v-slot:icon>-->
          <!--<CalendarIcon/>-->
        <!--</template>-->
      <!--</BaseInput>-->

      <!--<BaseDropdown-->
        <!--v-model="ddMultiple.value"-->
        <!--:header="ddMultiple.header"-->
        <!--:options="ddMultiple.options"-->
        <!--:multiple="true"-->
        <!--:close-on-select="false"-->
        <!--:searchable="false"-->
        <!--:max-height="500"-->
      <!--/>-->

      <!--<BaseDropdown-->
        <!--v-model="ddSingle.value"-->
        <!--:header="ddSingle.header"-->
        <!--:options="ddSingle.options"-->
        <!--:searchable="false"-->
        <!--:max-height="500"-->
      <!--/>-->
    </div>
  </div>
</template>

<script>
import SocialMediaLinks from './SocialMediaLinks.vue';
import BaseInput from '../../sharedComponents/BaseInput.vue';
import BaseTextarea from '../../sharedComponents/BaseTextarea.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';
import BaseTag from '../../sharedComponents/BaseTag.vue';
import BaseButtonFormSecondary from '../../sharedComponents/BaseButtonFormSecondary.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';
import CalendarIcon from '../../sharedComponents/icons/CalendarIcon.vue';

export default {
  components: {
    SocialMediaLinks,
    BaseInput,
    BaseTextarea,
    BaseDropdown,
    BaseTag,
    BaseButtonFormSecondary,
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
        genres: []
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
        'Классика2'
      ],

      inputVal: 'default',
      ddMultiple: {
        header: 'dd-multiple',
        value: [],
        options: ['Select option-mMMMMMMMM MMMMMMM MMMMMMMMMM', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched']
      },
      ddSingle: {
        header: 'dd-single',
        value: '',
        options: ['Select option-m', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched']
      }
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
    },

    callMethod(e) {
      console.log(e);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./CreateGroup.scss"
/>
