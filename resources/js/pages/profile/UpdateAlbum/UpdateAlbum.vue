<template>
  <div class="trackInfo">
    <ReturnHeader />
    <div class="add-track-loading" v-if="isLoading">
      <SpinnerLoader />
    </div>
    <div class="add-track" v-else>
      <div class="add-track-cover">
        <ChooseAvatar
          :imageUrl="album.cover[0].url"
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
        :class="{disabled: isLoading}"
        modifier="secondary"
        @press="removeAlbum"
      >
        Удалить
      </FormButton>
      <FormButton
        class="trackInfoFooter__button"
        :class="{disabled: isLoading}"
        modifier="primary"
        @press="addInfo"
      >
        Сохранить изменения
      </FormButton>
    </div>
  </div>
</template>
<script>
  import SpinnerLoader from 'components/SpinnerLoader.vue';
  import FormButton from 'components/FormButton.vue';
  import currentPath from 'mixins/currentPath';
  import BaseDropdown from 'components/BaseDropdown.vue';
  import ChooseGenres from '../ChooseGenres/ChooseGenres.vue';
  import BaseInput from 'components/BaseInput.vue';
  import FileInput from 'components/FileInput.vue';
  import ChooseAvatar from '../ChooseAvatar.vue';
  import PencilIcon from 'components/icons/PencilIcon.vue';
  import CalendarIcon from 'components/icons/CalendarIcon.vue';
  import NotepadIcon from 'components/icons/NotepadIcon.vue';
  import CreateAlbum from '../../upload/CreateAlbum.vue';
  import ChooseYear from '../ChooseYear/ChooseYear.vue';
  import ReturnHeader from '../ReturnHeader.vue';
  import gql from 'graphql-tag';

  export default{
    mixins: [currentPath],
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
      isLoading: true,
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
      createAlbum: false,
      bands: [
        {
          id: Number,
          name: String
        }
      ],
      albums: {
        albums: {
          data: [
            {
              title: String
            }
          ]
        }
      },
      addToAlbum: false,
    }),

    computed: {
      albumId() {
        const pathPrefix = +this.$route.params.editAlbumId;
        return pathPrefix;
      }
    },

    methods: {
      removeAlbum() {
        this.$apollo.mutate({
          variables: {
            id: this.albumId
          },
          mutation: gql`mutation($id: Int!) {
            deleteAlbum (albumId: $id) {
              id
            }
          }`
        })
        .then(response => {
          this.$router.push('/profile/my-music');
          this.$message(
            'Ваш альбом удалён',
            'info',
            { timeout: 5000 }
          );
        })
        .catch(error => {
          console.log(error);
        });
      },

      handleFormatChoice() {

      },
      addInfo(){
        if(!this.isLoading){
          let genres = this.albumInfo.genres.map((genre) => {
            return genre.id;
          });
          let format = this.albumFormats.filter(album => {
            return album.name === this.albumInfo.format;
          });
          let singer = null;
          let band = null;
          let bandId = null;
          if(this.bands[0].name === this.albumInfo.selectedArtist){
            singer = this.albumInfo.selectedArtist;
          }else{
            band = this.bands.filter((band) => {
              return band.name === this.albumInfo.selectedArtist;
            });
            bandId = band[0].id;
            singer = band[0].name;
          };
          let info = {
            'type': format[0].value,
            'year': this.albumInfo.year.input,
            'musicGroup': bandId,
            'genres': genres,
            'author': this.albumInfo.selectedArtist,
            'title': this.albumInfo.name.input
          };
          if(this.albumInfo.text !== null) {
            info = {
              ...info,
              songText: this.albumInfo.text
            }
          };
          if(this.albumInfo.selectedAlbum.id !== null) {
            info = {
              ...info,
              album: this.albumInfo.selectedAlbum.id
            }
          };
          let cover = this.albumInfo.cover;
          this.$apollo.mutate({
            variables: {
              id: this.albumId,
              album: info,
              cover: cover
            },
            mutation: gql`mutation($id: Int!, $album: AlbumInput, $cover: Upload) {
              updateAlbum (id: $id, album: $album, cover: $cover) {
                id
              }
            }`
          }).then((response) => {
            this.$router.push('/profile/my-music');
            this.$message(
              'Информация о Вашем альбоме обновлена',
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
        }
      },
      onCoverInput(file) {
        this.albumInfo.cover = file;
      },
      updateAlbums(){
        this.$apollo.query({
          fetchPolicy: 'network-only',
          query: gql`query{
            albums(limit: 0, page: 0, filters: { my: true }){
              data {
                id
                title
              }
            }
          }`
        }).then((res) => {
          console.log(res);
        }).catch((err) => {
          console.log(err);
        });
      }
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
      CreateAlbum,
      ChooseYear,
      SpinnerLoader,
      ReturnHeader
    },

    apollo: {
      getAlbums() {
        return {
          query: gql`query{
            albums(limit: 0, page: 0, filters: { my: true }){
              data {
                id
                title
              }
            }
          }`,
          update(data){
            return this.albums = data;
          }
        }
      },

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
            let myData = {id: 0, name: data.myProfile.username};
            this.bands = [myData, ...data.myProfile.musicGroups];
            this.albumInfo.selectedArtist = myData.name;
          }
        }
      },

      album() {
        this.isLoading = true;
        return {
          variables: {
            id: this.albumId
          },
          fetchPolicy: 'network-only',
          query: gql`query Album($id: Int!){
            album(id: $id){
              id
              title
              type
              genres{
                id
                name
              }
              musicGroup{
                id
                name
              }
              year
              cover(sizes: size_235x235){
                size,
                url
              }
            }
          }`,
          update(data) {
            this.isLoading = false;
            this.albumInfo.name.input = data.album.title;
            this.albumInfo.year.input = (new Date(data.album.year).getFullYear()).toString();
            this.albumInfo.genres = data.album.genres;
            let format = this.albumFormats.filter(album => {
              return album.value === data.album.type;
            });
            this.albumInfo.format = format[0].name;
            return data.album;
          }
        }
      }
    },
  }
</script>
<style
  scoped
  lang="scss"
  src="./UpdateAlbum.scss"
/>
