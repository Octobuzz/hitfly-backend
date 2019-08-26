<template>
  <main class="main"
    :class="{notDropped: track === null}"
    @dragenter="onDragEnter"
    @dragleave="onDragLeave"
    @dragover.prevent
    >
    <aside class="main__aside aside">
      <div class="aside__content">
        <Dropzone
        @droppedTrack="trackDropped"
        @trackInput="trackInputed"
        @accessChanged="changeAccess"
        :dragState="dragging"
        ></Dropzone>
      </div>
    </aside>
    <div class="main__info">
      <ProgressBar v-if="track" :loading="loading"></ProgressBar>
      <PageHeader class="add-track__page-header" v-show="track !== null">
        Загрузка песни
      </PageHeader>
      <TrackInfo
      v-show="track !== null"
      @sendInfo="addInfo"
      :loading="loading"
      :filename="filename"
      :validation="validation"
      ></TrackInfo>
      <div class="up-page">
        <div class="up-page__bottom">
          <p class="up-page__agree">
              Загружая файл, вы подтверждаете, что ваши песни соответствуют нашим <a href="/">Условиям использования</a>, и вы не нарушаете авторские права.
          </p>
          <div class="up-b-info">
            <div class="up-b-info__left">
              <a href="/" class="up-b-info__link">Пользовательское соглашение</a>
              <a href="/" class="up-b-info__link">Авторам</a>
              <a href="/" class="up-b-info__link">Помощь</a>
            </div>
            <div class="up-b-info__right">
              <a href="/" class="up-b-info__link">Как  загрузить песню?</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
<script>
  import Dropzone from './Dropzone.vue';
  import TrackInfo from './TrackInfo.vue';
  import ProgressBar from './ProgressBar.vue';
  import PageHeader from 'components/PageHeader.vue';
  import gql from 'graphql-tag';

  export default{
    data: () => ({
      track: null,
      access: true,
      dragging: false,
      dragCount: 0,
      trackID: null,
      loading: false,
      filename: '',
      validation:{
        trackName: {
          message: '',
          error: false
        },
        genre: {
          message: '',
          error: false
        }
      }
    }),
    components: {
      Dropzone,
      TrackInfo,
      PageHeader,
      ProgressBar
    },
    methods: {
      onDragEnter(e){
        e.preventDefault();
        this.dragCount++;
        this.dragging = true;
        document.body.classList.add('blurred');
      },
      onDragLeave(e){
        e.preventDefault();
        this.dragCount--;
        if(this.dragCount <= 0){
          this.dragging = false;
          document.body.classList.remove('blurred');
        }
      },
      changeAccess(state){
        this.access = state;
      },
      trackDropped(track){
        this.dragCount--;
        this.dragging = false;
        this.track = track;
        document.body.classList.remove('blurred');
        if(this.track !== null){
          this.uploadTrack(track[0]);
        }
      },
      trackInputed(track){
        this.track = track;
        if(this.track !== null){
          this.uploadTrack(track);
        }
      },
      addInfo(info){
        this.$apollo.mutate({
          variables: {
            id: this.trackID,
            infoTrack: {
              singer: info.singer,
              musicGroup: info.musicGroup,
              genres: info.genre,
              trackDate: info.trackDate,
              songText: info.songText,
              trackName: info.trackName,
              album: info.album,
              cover: info.cover
            }
          },
          mutation: gql`mutation($id: Int!, $infoTrack: TrackInput) {
            updateTrack (id: $id, infoTrack: $infoTrack) {
              id
              trackName
              singer
              trackDate
              musicGroup{
                id
                name
              }
            }
          }`
        }).then((response) => {
          this.$router.push('/profile/my-music');
          this.$message(
            'Ваша песня загружена и будет доступна через 10 минут',
            'info'
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
          console.log(this.validation);
        })
      },
      uploadTrack(track){
        this.loading = true;
        this.$apollo.mutate({
          variables: {
            track: track
          },
          mutation: gql`mutation($track: Upload) {
            uploadTrack (track: $track){
              id
              filename
            }
          }`
        }).then((response) => {
          this.loading = false;
          this.filename = response.data.uploadTrack.filename;
          this.trackID = response.data.uploadTrack.id;
          console.log(this.trackID);
        }).catch((error) => {
          console.dir(error);
          this.loading = false;
        });
      },
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./UploadPage.scss"
/>
