<template>
  <div class="trackInfo">
    <div class="add-track">
      <div class="add-track-cover">
        <ChooseAvatar
          caption="Загрузить обложку"
          @input="onCoverInput"
        />
      </div>

      <div class="add-track-description">
        <BaseInput
          v-model="trackInfo.name.input"
          label="Название песни"
          class="add-track-description__name-input"
        >
          <template #icon>
            <PencilIcon/>
          </template>
        </BaseInput>
        <p class="add-track-filename" v-show="filename.length > 0">{{ filename }}</p>

        <BaseInput
          v-model="trackInfo.year.input"
          label="Год создания песни"
          class="add-track-description__year-input"
        >
          <template #icon>
            <CalendarIcon/>
          </template>
        </BaseInput>

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
            <div class="buttonSwitcher" @click="createAlbum = false" :class="{active: !createAlbum}">Мои альбомы</div>
          </div>
          <CreateAlbum
           v-show="createAlbum"
           class="addAlbumWrapper__content"
           :bands="bands"
           @changeTab="changeTab"
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
        :class="{disabled: loading}"
        modifier="primary"
        @press="addInfo"
      >
        Опубликовать
      </FormButton>
    </div>
  </div>
</template>
<script>
  import FormButton from 'components/FormButton.vue';
  import BaseDropdown from 'components/BaseDropdown.vue';
  import ChooseGenres from './../profile/ChooseGenres/ChooseGenres.vue';
  import BaseInput from 'components/BaseInput.vue';
  import FileInput from 'components/FileInput.vue';
  import ChooseAvatar from '../profile/ChooseAvatar.vue';
  import PencilIcon from 'components/icons/PencilIcon.vue';
  import CalendarIcon from 'components/icons/CalendarIcon.vue';
  import NotepadIcon from 'components/icons/NotepadIcon.vue';
  import CreateAlbum from './CreateAlbum.vue';
  import gql from 'graphql-tag';

  export default{
    props: ['loading', 'filename'],
    data: () => ({
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
      },
      myData: {},
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
    methods: {
      handleTextfileInput(file){
        this.trackInfo.text = file;
      },
      totalBands() {
        return this.bands.push(this.myData);
      },
      changeTab() {
        this.createAlbum = false
      },
      addInfo(){
        const genres = this.trackInfo.genres.map((genre) => {
          return genre.id;
        });
        const info = {
          'singer': this.trackInfo.selectedArtist,
          'trackDate': this.trackInfo.year.input,
          'songText': this.trackInfo.text,
          'genre': genres,
          'trackName': this.trackInfo.name.input,
          'album': this.trackInfo.selectedAlbum.id
        };
        this.$emit('sendInfo', info);
      },
      onCoverInput(){
        console.log('ok');
      },
      handleAlbumSelect(){
        const selectedAlbum = this.albums.albums.data.filter((album) => {
          if(album.title === this.trackInfo.displayAlbum){
            return album;
          }
        });
        this.trackInfo.selectedAlbum = selectedAlbum[0];
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
      CreateAlbum
    },
    apollo: {
      getAlbums: {
        query: gql`query{
          albums(limit: 0, page: 0, my: true){
            data {
              id
              title
            }
          }
        }`,
        update(data){
          console.log(data);
          this.albums = data;
        }
      },
    },
    mounted() {
      this.$apollo.query({
        query: gql`query{
          myProfile{
            musicGroups{
              id
              name
            },
            username,
            id
          }
        }`
      }).then((response) => {
        this.bands = response.data.myProfile.musicGroups;
        this.myData = {id: response.data.myProfile.id, name: response.data.myProfile.username};
        this.totalBands();
      })
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./TrackInfo.scss"
/>
