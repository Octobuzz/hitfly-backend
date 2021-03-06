<template>
  <main
    class="main"
    :class="{notDropped: track === null}"
    @dragenter="onDragEnter"
    @dragleave="onDragLeave"
    @dragover.prevent
  >
    <aside class="main__aside aside">
      <div class="aside__content">
        <Dropzone
          :drag-state="dragging"
          @droppedTrack="trackDropped"
          @trackInput="trackInputed"
          @accessChanged="changeAccess"
        />
      </div>
    </aside>
    <div class="main__info">
      <ProgressBar v-if="track" :loading="loading" />
      <PageHeader v-show="track !== null" class="add-track__page-header">
        Загрузка песни
      </PageHeader>
      <TrackInfo
        v-show="track !== null"
        :loading="loading"
        :filename="filename"
        :validation="validation"
        @sendInfo="addInfo"
      />
      <div class="up-page">
        <div class="up-page__bottom">
          <p class="up-page__agree">
            Загружая файл, вы подтверждаете, что ваши песни соответствуют нашим <a href="/policy" target="_blank">Условиям использования</a>, и вы не нарушаете авторские права.
          </p>
          <div class="up-b-info">
            <div class="up-b-info__left">
              <a
                href="/policy"
                target="_blank"
                class="up-b-info__link"
              >Пользовательское соглашение</a>
              <!--              <a href="/" class="up-b-info__link">Авторам</a>-->
              <a href="/contacts" class="up-b-info__link">Помощь</a>
            </div>
            <!-- <div class="up-b-info__right"> -->
              <!--              <a href="/" class="up-b-info__link">Как загрузить песню?</a>-->
            <!-- </div> -->
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
<script>
import PageHeader from 'components/PageHeader.vue';
import gql from 'graphql-tag';
import Dropzone from './Dropzone.vue';
import TrackInfo from './TrackInfo.vue';
import ProgressBar from './ProgressBar.vue';

export default {
  components: {
    Dropzone,
    TrackInfo,
    PageHeader,
    ProgressBar
  },
  data: () => ({
    track: null,
    access: true,
    dragging: false,
    dragCount: 0,
    trackID: null,
    loading: false,
    filename: '',
    validation: {
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
  methods: {
    onDragEnter(e) {
      e.preventDefault();
      this.dragCount++;
      this.dragging = true;
      document.body.classList.add('blurred');
    },
    onDragLeave(e) {
      e.preventDefault();
      this.dragCount--;
      if (this.dragCount <= 0) {
        this.dragging = false;
        document.body.classList.remove('blurred');
      }
    },
    changeAccess(state) {
      this.access = state;
    },
    trackDropped(track) {
      this.dragCount--;
      this.dragging = false;
      this.track = track;
      document.body.classList.remove('blurred');
      if (this.track !== null) {
        this.uploadTrack(track[0]);
      }
    },
    trackInputed(track) {
      this.track = track;
      if (this.track !== null) {
        this.uploadTrack(track);
      }
    },
    addInfo(info) {
      this.$apollo.mutate({
        variables: {
          id: this.trackID,
          infoTrack: {
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
          'Мы обрабатываем ваш трек. Ваша песня проявится в профиле через несколько минут.',
          'info'
        );
      }).catch((error) => {
        console.dir(error);
        const errors = error.graphQLErrors[0].validation;
        if (errors['infoTrack.genres'].length > 0) {
          this.validation.genre.message = errors['infoTrack.genres'][0];
          this.validation.genre.error = true;
        }
        if (errors['infoTrack.trackName'].length > 0) {
          this.validation.trackName.message = errors['infoTrack.trackName'][0];
          this.validation.trackName.error = true;
        }
      });
    },
    uploadTrack(track) {
      this.loading = true;
      this.$apollo.mutate({
        variables: {
          track
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
      }).catch((error) => {
        console.dir(error);
        this.loading = false;
      });
    },
  }
};
</script>
<style
  scoped
  lang="scss"
  src="./UploadPage.scss"
/>
