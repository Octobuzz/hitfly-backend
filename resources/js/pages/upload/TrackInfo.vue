<template>
  <div class="trackInfo">
    <div class="trackInfoBody">

    </div>
    <div class="trackInfoFooter">
      <FormButton
        class="trackInfoFooter__button"
        modifier="primary"
      >
        Опубликовать
      </FormButton>
    </div>
  </div>
</template>
<script>
  import FormButton from './../../sharedComponents/FormButton.vue';

  export default{
    data: () => ({

    }),
    methods: {
      addInfo(){
        this.$emit('sendInfo', info);
      }
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./TrackInfo.scss"
/>
