<template>
  <div class="progressBarWrapper">
    <div class="progressBarBody" :class="{loading: loading}">
      <div class="progressBarBody__total"></div>
      <div class="progressBarBody__done" :class="{animated: loading}"></div>
    </div>
    <div class="progressBarNotice" :class="{done: !loading}">
      <img src="../../../images/progress.png" alt="">
      <p v-show="loading">Подождите, ваша песня загружается</p>
      <p v-show="!loading">Ваша песня загружена</p>
    </div>
  </div>
</template>
<script>
  export default{
    data: () => ({

    }),
    props: ['loading'],
  }
</script>
<style
  scoped
  lang="scss"
  src="./ProgressBar.scss"
/>
