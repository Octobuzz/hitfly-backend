<template>
  <main class="main" :class="{notDropped: track === null}">
    <aside class="main__aside aside">
      <div class="aside__content">
        <Dropzone @droppedTrack="trackDropped"></Dropzone>
      </div>
    </aside>
    <div class="main__info">
      <transition  v-show="track !== null" name="fade">
        <TrackInfo @infoAdded="sendInfo"></TrackInfo>
      </transition>
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

  export default{
    data: () => ({
      dragging: false,
      track: null,
      trackInformation: null,
    }),
    components: {
      Dropzone, TrackInfo
    },
    methods: {
      trackDropped(track){
        this.track = track;
      },
      infoAdded(info){
        this.trackInformation = info;
      }
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./UploadPage.scss"
/>
