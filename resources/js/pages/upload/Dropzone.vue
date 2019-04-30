<template>
  <div class="up-page__top"
      @dragenter="onDragEnter"
      @dragleave="onDragLeave"
      @dragover.prevent
      @drop="onDrop"
    >
    <div class="up-page__top-wrapper">
      <h1 class="up-page__title">Переместите сюда свою песню или альбом</h1>
      <FormButton
        class="up-page__button"
        modifier="primary"
      >
        или загрузите файл с устройства
      </FormButton>
      <div class="radio-pair">
        <span class="input-radio">
          <input name="gender" id="man" value="1" type="radio">
          <label for="man">открытый доступ</label>
        </span>
        <span class="input-radio">
          <input name="gender" id="woman" value="2" type="radio">
          <label for="woman">доступ по ссылке</label>
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
  import FormButton from './../../sharedComponents/FormButton.vue';

  export default {
    data: () => ({

    }),
    methods: {
      onDragEnter(e){
        e.preventDefault();
      },
      onDragLeave(e){
        e.preventDefault();
      },
      onDrop(e){
        e.preventDefault();
        e.stopPropagation();
        const track = e.dataTransfer.files;
        this.$emit('droppedTrack', track);
      },
    },
    components: {
      FormButton
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./Dropzone.scss"
/>
