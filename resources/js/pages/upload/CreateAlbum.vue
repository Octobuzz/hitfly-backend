<template>
  <div class="createAlbumWrapper">
    <BaseInput
      v-model="newAlbum.name"
      label="Название альбома"
      class="add-track-description__name-input"
    >
      <template #icon>
        <PencilIcon/>
      </template>
    </BaseInput>
    <BaseDropdown
      v-model="newAlbum.format"
      label="Тип альбома"
      class="add-track-description__dropdown"
      :options="albumFormats"
      :multiple="false"
      :close-on-select="true"
      :searchable="false"
      :max-height="500"
      @input="handleFormatChoice"
    />
    <ChooseGenres
      v-model="genres"
      class="add-track-description__genre-tag-container"
      dropdown-class="add-track-description__dropdown"
    >
      <template #separator>
        <span
          :class="[
            'add-track-description__genre-list-suggestion',
            'add-track-description__regular-text'
          ]"
        >
        </span>
      </template>
    </ChooseGenres>
    <div class="createAlbum__button button" @click="createAlbum">Создать альбом</div>
  </div>
</template>
<script>
  import BaseDropdown from 'components/BaseDropdown.vue';
  import ChooseGenres from './../profile/ChooseGenres/ChooseGenres.vue';
  import BaseInput from 'components/BaseInput.vue';
  import PencilIcon from 'components/icons/PencilIcon.vue';
  import CalendarIcon from 'components/icons/CalendarIcon.vue';

  export default {
    data: () => ({
      albumFormats: ['EP', 'LP', 'Single'],
      newAlbum: {
        name: '',
        format: '',
        genre: '',
      },
      genres: [],
    }),
    methods: {
      createAlbum() {
        
      },
      handleFormatChoice() {
        console.log('changed');
      }
    },
    components: {
      BaseDropdown,
      ChooseGenres,
      BaseInput,
      PencilIcon,
      CalendarIcon
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./CreateAlbum.scss"
/>
