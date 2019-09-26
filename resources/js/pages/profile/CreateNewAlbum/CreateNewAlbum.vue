<template>
  <div class="trackInfo">
    <ReturnHeader />
    <div class="add-track-loading" v-if="isLoading">
      <SpinnerLoader />
    </div>
    <div class="add-track" v-else>
      <div class="add-track-cover">
        <ChooseAvatar
          caption="Загрузить обложку"
          @input="onCoverInput"
        />
      </div>
      <div class="createAlbumWrapper">
        <BaseInput
          v-model="albumInfo.name.input"
          label="Название альбома"
          class="add-track-description__name-input"
        >
          <template #icon>
            <PencilIcon/>
          </template>
        </BaseInput>
        <BaseDropdown
          v-model="albumInfo.selectedArtist"
          class="add-track-description__dropdown add-track-description__year-input"
          title="Автор альбома"
          :options="bands.map((band) => band.name)"
          :multiple="false"
          :close-on-select="true"
          :searchable="false"
          :max-height="500"
        />
        <BaseDropdown
          v-model="albumInfo.format"
          class="add-track-description__dropdown add-track-description__year-input"
          title="Тип альбома"
          :options="albumFormats.map((album) => album.name)"
          :searchable="false"
          :max-height="500"
          @input="handleFormatChoice"
        />
        <ChooseYear
          v-model="albumInfo.year.input"
          class="add-track-description__year-input"
          label="Год создания альбома"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </ChooseYear>
        <ChooseGenres
          v-model="albumInfo.genres"
          class="add-track-description__genre-tag-container"
          dropdown-class="add-track-description__dropdown"
        />
      </div>
    </div>
    <div class="trackInfoFooter">
      <FormButton
        class="trackInfoFooter__button"
        modifier="primary"
        :class="{disabled: isLoading}"
        @press="createAlbum"
      >
        Сохранить изменения
      </FormButton>
    </div>
  </div>
</template>
<script>
  import SpinnerLoader from 'components/SpinnerLoader.vue';
  import FormButton from 'components/FormButton.vue';
  import BaseDropdown from 'components/BaseDropdown.vue';
  import ChooseGenres from '../ChooseGenres/ChooseGenres.vue';
  import BaseInput from 'components/BaseInput.vue';
  import FileInput from 'components/FileInput.vue';
  import ChooseAvatar from '../ChooseAvatar.vue';
  import PencilIcon from 'components/icons/PencilIcon.vue';
  import CalendarIcon from 'components/icons/CalendarIcon.vue';
  import NotepadIcon from 'components/icons/NotepadIcon.vue';
  import ChooseYear from '../ChooseYear/ChooseYear.vue';
  import ReturnHeader from '../ReturnHeader.vue';
  import gql from 'graphql-tag';

  export default{
    data: () => ({
      isLoading: true,
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
      myData: {},
      validation:{
        trackName: {
          message: '',
          error: false
        },
        genre: {
          message: '',
          error: false
        }
      },
      albumInfo:{
        year: {
          input: ''
        },
        format: '',
        genres: [],
        name: {
          input: ''
        },
        text: null,
        displayAlbum: null,
        selectedArtist: null,
        selectedAlbum: {
          id: Number
        },
        cover: null,
      },
      textFileError: false,
      bands: [
        {
          id: Number,
          name: String
        }
      ],
      addToAlbum: false,
    }),

    methods: {
      handleFormatChoice() {

      },

      createAlbum() {
        const genres = this.albumInfo.genres.map((genre) => {
          return genre.id;
        });
        const format = this.albumFormats.filter((format) => {
          if(format.name === this.albumInfo.format){
            return true;
          }
        });
        let bandId = null;
        if(!this.bands[0].id === 0){
          let band = this.bands.filter((band) => {
            return band.name === this.albumInfo.author;
          });
          bandId = band[0].id;
        };
        this.$apollo.mutate({
          variables: {
            album: {
              type: format[0].value,
              title: this.albumInfo.name.input,
              musicGroup: bandId,
              year: this.albumInfo.year.input,
              genres: genres,
              author: this.myData.name
            },
            cover: this.albumInfo.cover
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
          this.$router.push('/profile/my-music');
          this.$message(
            'Новый альбом добавлен',
            'info',
            { timeout: 5000 }
          );
        }).catch((error) => {
          console.dir(error);
          let errors = error.graphQLErrors[0].validation;
          if(errors['infoTrack.genres'].length > 0){
            this.validation.genre.message = errors['infoTrack.genres'][0];
            this.validation.genre.error = true;
          };
          if(errors['infoTrack.trackName'].length > 0){
            this.validation.trackName.message = errors['infoTrack.trackName'][0];
            this.validation.trackName.error = true;
          };
        })
      },
      onCoverInput(file) {
        this.albumInfo.cover = file;
      },
    },

    components: {
      FormButton,
      ChooseGenres,
      BaseDropdown,
      BaseInput,
      FileInput,
      ChooseAvatar,
      PencilIcon,
      CalendarIcon,
      NotepadIcon,
      ChooseYear,
      SpinnerLoader,
      ReturnHeader
    },

    apollo: {
      musicGroups() {
        return {
          query: gql`query{
            myProfile{
              musicGroups{
                id
                name
              },
              username,
              id
            }
          }`,
          update(data) {
            this.myData = {id: 0, name: data.myProfile.username};
            this.bands = [this.myData, ...data.myProfile.musicGroups];
            this.albumInfo.selectedArtist = this.myData.name;
            this.isLoading = false;
          }
        }
      },
    },
  }
</script>
<style
  scoped
  lang="scss"
  src="./CreateNewAlbum.scss"
/>
