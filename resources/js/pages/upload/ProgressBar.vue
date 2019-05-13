<template>
  <div class="progressBarWrapper">
    <div class="progressBarBody">
      <div class="progressBarBody__total"></div>
      <div class="progressBarBody__done" :class="{animated: loading}"></div>
    </div>
    <div class="progressBarNotice">
      <img src="../../../images/progress.png" alt="">
      <p>Подождите, ваша песня загружается</p>
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
