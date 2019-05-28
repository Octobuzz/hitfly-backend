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
    <no-ssr>
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
    </no-ssr>
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
    />
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
  import BaseDropdown from '~/components/shared/BaseDropdown.vue';
  import ChooseGenres from '~/components/profile/ChooseGenres/ChooseGenres.vue';
  import BaseInput from '~/components/shared/BaseInput.vue';
  import PencilIcon from '~/components/shared/icons/PencilIcon.vue';
  import CalendarIcon from '~/components/shared/icons/CalendarIcon.vue';
  import ChooseAvatar from '~/components/profile/ChooseAvatar.vue';
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
        this.$apollo.mutate({
          variables: {
            album: {
              type: format[0].value,
              title: this.newAlbum.name,
              author: this.newAlbum.author,
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
              cover(sizes:[size_48x48]){size, url}
            }
          }`
        }).then((response) => {
          console.log(response.data);
          this.$emit('changeTab');
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
      ChooseAvatar
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./CreateAlbum.scss"
/>
