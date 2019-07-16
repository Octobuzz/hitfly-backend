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
      v-model="newAlbum.author"
      class="add-track-description__dropdown"
      title="Автор альбома"
      :options="bands.map((band) => band.name)"
      :multiple="false"
      :close-on-select="true"
      :searchable="false"
      :max-height="500"
    />
    <BaseDropdown
      v-model="newAlbum.format"
      class="add-track-description__dropdown"
      title="Тип альбома"
      :options="albumFormats.map((album) => album.name)"
      :searchable="false"
      :max-height="500"
      @input="handleFormatChoice"
    />
    <ChooseYear
      v-model="newAlbum.year"
      class="add-track-description__year-input"
      label="Год создания альбома"
    >
      <template #icon>
        <CalendarIcon/>
      </template>
    </ChooseYear>
    <ChooseGenres
      v-model="newAlbum.genre"
      class="add-track-description__genre-tag-container"
      dropdown-class="add-track-description__dropdown"
    />
    <div class="add-track-cover">
      <ChooseAvatar
        caption="Загрузить обложку"
        @input="onCoverInput"
      />
    </div>
    <div class="createAlbum__button button form-button_primary" @click="createAlbum">Создать альбом</div>
  </div>
</template>
<script>
  import BaseDropdown from 'components/BaseDropdown.vue';
  import ChooseGenres from '../profile/ChooseGenres/ChooseGenres.vue';
  import BaseInput from 'components/BaseInput.vue';
  import PencilIcon from 'components/icons/PencilIcon.vue';
  import CalendarIcon from 'components/icons/CalendarIcon.vue';
  import ChooseAvatar from '../profile/ChooseAvatar.vue';
  import ChooseYear from '../profile/ChooseYear/ChooseYear.vue';
  import gql from 'graphql-tag';

  export default {
    props: ['bands'],
    data: () => ({
      albumFormats: [
        {
          name: 'LP',
          value: 'album'
        },
        {
          name: 'EP',
          value: 'EP'
        },
        {
          name: 'Коллекция',
          value: 'collection'
        },
        {
          name: 'Сингл',
          value: 'single'
        }
      ],
      newAlbum: {
        name: '',
        format: '',
        genre: [],
        year: '',
        author: '',
        albumCover: null,
      },
    }),
    methods: {
      onCoverInput(file) {
        this.albumCover = file
      },
      createAlbum() {
        const genres = this.newAlbum.genre.map((genre) => {
          return genre.id;
        });
        const format = this.albumFormats.filter((format) => {
          if(format.name === this.newAlbum.format){
            return true;
          }
        });
        let singer = null;
        let band = null;
        let bandId = null;
        if(this.bands[0].name === this.newAlbum.author){
          singer = this.newAlbum.author;
        }else{
          band = this.bands.filter((band) => {
            return band.name === this.newAlbum.author;
          });
          bandId = band[0].id;
          singer = band[0].name;
        }
        this.$apollo.mutate({
          variables: {
            album: {
              type: format[0].value,
              title: this.newAlbum.name,
              author: singer,
              musicGroup: bandId,
              year: this.newAlbum.year,
              genres: genres,
              author: this.newAlbum.author,
            },
            cover: this.albumCover
          },
          mutation: gql`mutation($album: AlbumInput, $cover: Upload) {
            createAlbum (album: $album, cover: $cover) {
              title
              id
              musicGroup{
                id
              }
              author
              cover(sizes:[size_48x48]){size, url}
            }
          }`
        }).then((response) => {
          this.$emit('changeTab');
          this.$emit('createAlbum');
        }).catch((error) => {
          console.dir(error)
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
      ChooseAvatar,
      ChooseYear
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./CreateAlbum.scss"
/>
