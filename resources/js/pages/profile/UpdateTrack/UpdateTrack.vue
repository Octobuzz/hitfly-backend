<template>
  <div class="trackInfo">
    <ReturnHeader />
    <div class="add-track-loading" v-if="isLoading">
      <SpinnerLoader />
    </div>
    <div class="add-track" v-else>
      <div class="add-track-cover">
        <ChooseAvatar
          :imageUrl="track.cover[0].url"
          caption="Загрузить обложку"
          @input="onCoverInput"
        />
      </div>

      <div class="add-track-description">
        <BaseInput
          v-model="trackInfo.name.input"
          label="Название песни"
          class="add-track-description__name-input"
          :showError="validation.trackName.error"
          :errorMessage="validation.trackName.message"
        >
          <template #icon>
            <PencilIcon/>
          </template>
        </BaseInput>

        <ChooseYear
          v-model="trackInfo.year.input"
          class="add-track-description__year-input"
          title="Год создания песни"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </ChooseYear>

        <FileInput
          label="Загрузить текст песни"
          class="add-track-description__text-input"
          @change="handleTextfileInput"
        >
          <template #icon>
            <NotepadIcon/>
          </template>
        </FileInput>

        <h3 class="add-track-description__header_h3">
          Выберите жанр
        </h3>
        <ChooseGenres
          v-model="trackInfo.genres"
          class="add-track-description__genre-tag-container"
          dropdown-class="add-track-description__dropdown"
          :noneSelectedError="validation.genre.error"
        />

        <h3 class="add-track-description__header_h3">
          Музыкальная группа
        </h3>
        <p class="add-track-description__subheader">Выберите автора песни: вы или группа</p>
        <BaseDropdown
          v-model="trackInfo.selectedArtist"
          class="add-track-description__dropdown"
          title="Автор песни"
          :options="bands.map(band => band.name)"
          :multiple="false"
          :close-on-select="true"
          :searchable="false"
          :max-height="500"
        />
        <span class="input-checkbox album-input-checkbox">
            <input id="tt" type="checkbox" v-model="addToAlbum">
            <label for="tt">Хочу добавить песню в альбом</label>
        </span>
        <div class="addAlbumWrapper" v-show="addToAlbum">
          <div class="addAlbumWrapper__buttons">
            <div class="buttonSwitcher" @click="createAlbum = true" :class="{active: createAlbum}">Создать альбом</div>
            <div v-if="albums.albums.data.length > 0" class="buttonSwitcher" @click="createAlbum = false" :class="{active: !createAlbum}">Мои альбомы</div>
          </div>
          <CreateAlbum
           v-show="createAlbum"
           class="addAlbumWrapper__content"
           :bands="bands"
           @changeTab="changeTab"
           @createAlbum="updateAlbums()"
          />
          <div class="addAlbumWrapper__content" v-show="!createAlbum">
            <div class="add-track-chooseAlbum">
              <BaseDropdown
                v-model="trackInfo.displayAlbum"
                class="add-track-description__dropdown"
                title="Мои альбомы"
                :options="albums.albums.data.map(album => album.title)"
                :multiple="false"
                :close-on-select="true"
                :searchable="false"
                :max-height="500"
                @input="handleAlbumSelect"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="trackInfoFooter">
      <FormButton
        class="trackInfoFooter__button"
        :class="{disabled: isLoading}"
        modifier="secondary"
        @press="removeTrack"
      >
        Удалить
      </FormButton>
      <FormButton
        class="trackInfoFooter__button"
        :class="{disabled: isLoading}"
        modifier="primary"
        @press="addInfo"
      >
        Опубликовать
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
      trackInfo:{
        year: {
          input: ''
        },
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
      trackId() {
        const pathPrefix = +this.$route.params.editTrackId;
        return pathPrefix;
      }
    },

    methods: {
      removeTrack() {
        this.$apollo.mutate({
          variables: {
            id: this.trackId
          },
          mutation: gql`mutation($id: Int!) {
            deleteTrackMutation (id: $id) {
              id
            }
          }`
        })
        .then(response => {
          this.$router.push('/profile/my-music');
          this.$message(
            'Ваша песня удалена',
            'info',
            { timeout: 5000 }
          );
        })
        .catch(error => {
          console.log(error);
        });
      },

      handleTextfileInput(file){
        if(file !== null && file.type.match('application/vnd.openxmlformats-officedocument.wordprocessingml.document|text/plain')){
          this.trackInfo.text = file;
        }else{
          this.trackInfo.text = null;
          this.$message(
            'Выберите текстовый файл в формате txt или docx',
          );
        }
      },
      changeTab() {
        this.createAlbum = false
      },
      addInfo(){
        if(!this.isLoading){
          let genres = this.trackInfo.genres.map((genre) => {
            return genre.id;
          });
          let singer = null;
          let band = null;
          let bandId = null;
          if(this.bands[0].name === this.trackInfo.selectedArtist){
            singer = this.trackInfo.selectedArtist;
          }else{
            band = this.bands.filter((band) => {
              return band.name === this.trackInfo.selectedArtist;
            });
            bandId = band[0].id;
            singer = band[0].name;
          };
          let info = {
            'singer': singer,
            'trackDate': this.trackInfo.year.input,
            'songText': this.trackInfo.text,
            'musicGroup': bandId,
            'genres': genres,
            'trackName': this.trackInfo.name.input
          };
          if(this.trackInfo.cover !== null) {
            info = {
              ...info,
              cover: this.trackInfo.cover
            }
          };
          if(this.trackInfo.text !== null) {
            info = {
              ...info,
              songText: this.trackInfo.text
            }
          };
          if(this.trackInfo.selectedAlbum.id !== null) {
            info = {
              ...info,
              album: this.trackInfo.selectedAlbum.id
            }
          };
          this.$apollo.mutate({
            variables: {
              id: this.trackId,
              infoTrack: info
            },
            mutation: gql`mutation($id: Int!, $infoTrack: TrackInput) {
              updateTrack (id: $id, infoTrack: $infoTrack) {
                id
                trackName
              }
            }`
          }).then((response) => {
            this.$router.push('/profile/my-music');
            this.$message(
              'Информация о Вашей песне обновлена',
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
      handleAlbumSelect(){
        const selectedAlbum = this.albums.albums.data.filter((album) => {
          if(album.title === this.trackInfo.displayAlbum){
            return album;
          }
        });
        this.trackInfo.selectedAlbum = selectedAlbum[0];
      },
      onCoverInput(file) {
        this.trackInfo.cover = file;
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
            this.trackInfo.selectedArtist = myData.name;
          }
        }
      },

      track() {
        return {
          variables: {
            id: this.trackId
          },
          fetchPolicy: 'network-only',
          query: gql`query Track($id: Int!){
            track(id: $id){
              id
              trackName
              album{
                id
                title
              }
              genres{
                id
                name
              }
              musicGroup{
                id
                name
              }
              singer
              trackDate
              cover(sizes: size_235x235){
                size,
                url
              }
            }
          }`,
          update(data) {
            this.isLoading = false;
            this.trackInfo.name.input = data.track.trackName;
            this.trackInfo.year.input = (new Date(data.track.trackDate).getFullYear()).toString();
            this.trackInfo.genres = data.track.genres;
            if(data.track.album !== null) {
              this.addToAlbum = true;
              this.trackInfo.displayAlbum = data.track.album.title;
              this.trackInfo.selectedAlbum.id = data.track.album.id;
            };
            return data.track;
          }
        }
      }
    },
  }
</script>
<style
  scoped
  lang="scss"
  src="./UpdateTrack.scss"
/>
