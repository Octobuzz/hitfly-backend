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
      class="add-track-description__dropdown"
      title="Тип альбома"
      :options="albumFormats"
      :searchable="false"
      :max-height="500"
      @input="handleFormatChoice"
    />
    <BaseInput
      v-model="newAlbum.year"
      label="Год создания альбома"
      class="add-track-description__year-input"
    >
      <template #icon>
        <CalendarIcon/>
      </template>
    </BaseInput>
    <ChooseGenres
      v-model="newAlbum.genre"
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
    <div class="add-track-cover">
      <ChooseAvatar
        caption="Загрузить обложку"
        @input="onCoverInput"
      />
    </div>
    <div class="createAlbum__button button" @click="createAlbum">Создать альбом</div>
  </div>
</template>
<script>
  import BaseDropdown from 'components/BaseDropdown.vue';
  import ChooseGenres from './../profile/ChooseGenres/ChooseGenres.vue';
  import BaseInput from 'components/BaseInput.vue';
  import PencilIcon from 'components/icons/PencilIcon.vue';
  import CalendarIcon from 'components/icons/CalendarIcon.vue';
  import ChooseAvatar from '../profile/ChooseAvatar.vue';
  import gql from 'graphql-tag';

  export default {
    data: () => ({
      albumFormats: ['EP', 'LP', 'Single'],
      newAlbum: {
        name: '',
        format: '',
        genre: [],
        year: ''
      },
    }),
    methods: {
      onCoverInput(file) {
        console.log(file)
      },
      createAlbum() {
        this.$apollo.mutate({
          variables: {
            album: {
              type: this.newAlbum.format,
              title: this.newAlbum.name,
              author: this.newAlbum.author,
              year: this.newAlbum.year,
              genres: this.newAlbum.genre
            },
            cover: this.albumCover
          },
          mutation: gql`mutation($album: AlbumInput, $cover: Upload) {
            updateTrack (id: $id, infoTrack: $infoTrack) {
              id
              trackName
              singer
              trackDate
            }
          }`
        }).then((response) => {
          // console.log(response.data)
          this.$router.push('/');
        }).catch((error) => {
          // console.dir(error)
        })
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
      CalendarIcon,
      ChooseAvatar
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./CreateAlbum.scss"
/>
