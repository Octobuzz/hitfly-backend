<template>
  <div class="up-page__top"
    :class="{dragging: dragState}"
    @drop="onDrop"
    >
    <div class="up-page__top-wrapper">
      <h1 class="up-page__title">Переместите сюда свою песню</h1>
      <UploadButton
        class="up-page__button"
        modifier="secondary"
        inputValue="или загрузите файл с устройства"
        @changed="fileInput"
      >
        или загрузите файл с устройства
      </UploadButton>
      <div class="radio-pair">
        <span class="input-radio">
          <input name="access" id="free" value="1" type="radio" checked @change="changeAccess(true)">
          <label for="free">открытый доступ</label>
        </span>
        <span class="input-radio">
          <input name="access" id="limited" value="2" type="radio" @change="changeAccess(false)">
          <label for="limited">доступ по ссылке</label>
        </span>
      </div>
    </div>
    <p class="up-page__info">
      Для наилучшего качества звука мы принимаем следующие форматы: FLAC, WAV, AIFF, ALAC.
      <a href="/">Узнать больше об аудиоформатах.</a>
    </p>
  </div>
</template>
<script>
  import UploadButton from './UploadButton.vue';

  export default {
    props: ['dragState'],
    data: () => ({

    }),
    methods: {
      onDrop(e){
        e.preventDefault();
        e.stopPropagation();
        if(e.dataTransfer.files[0].type.match('audio/x-flac|audio/flac|audio/vnd.wave|audio/x-aiff|audio/aiff|audio/x-m4a|audio/wave|audio/wav|audio/x-wav|audio/x-pn-wav')){
          const track = e.dataTransfer.files;
          this.$emit('droppedTrack', track);
        } else {
          this.$message(
            'Выберите корректный формат файла',
          );
          this.$emit('droppedTrack', null);
        }
      },
      fileInput(track){
        if(track !== undefined){
          if(track.type.match('audio/x-flac|audio/flac|audio/vnd.wave|audio/x-aiff|audio/aiff|audio/x-m4a|audio/wave|audio/wav|audio/x-wav|audio/x-pn-wav')){
            this.$emit('trackInput', track);
          }else{
            this.$message(
              'Выберите корректный формат файла',
              'info',
            );
          }
        }
      },
      changeAccess(state){
        this.$emit('accessChanged', state);
      },
    },
    components: {
      UploadButton
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./Dropzone.scss"
/>
