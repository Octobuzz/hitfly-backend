<template>
  <section class="footerSection">
    <div class="footerSection__links">
      <a href="/policy" target="_blank">Пользовательское соглашение</a>
      <!--      <a href="javascript: void(0)">Авторам</a>-->
      <a href="/contacts">Помощь</a>
    </div>
    <a href="https://zebrains.team/" target="_blank">Сделано в ZeBrains</a>
  </section>
</template>
<script>
export default {

};
</script>
<style scoped lang="scss">
  @import '~scss/_variables.scss';

  .footerSection {
    border-top: 1px solid $layout_border_color;
    padding: 16px 0;
    margin: 16px 32px 52px;
    display: -webkit-flex;
    display: -ms-flex;
    display: flex;
    -ms-align-items: center;
    align-items: center;
    justify-content: space-between;

    & a {
      color: #231F20;
      font-size: 14px;
      line-height: 1.2;

      &:hover {
        color: inherit;
      }
    }

    & a {
      margin-right: 20px;
      transition: .3s all ease;

      &:hover {
        color: $link_color_active;
      }
    }
  }

  @media (max-width: 767px) {
    .footerSection {
      margin: 0 16px;
      flex-direction: column;

      & a {
        font-size: 12px;
        margin-right: 0;
      }

      &__links {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 25px;
      }
    }
  }
</style>
