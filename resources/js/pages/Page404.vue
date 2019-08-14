<template>
  <div class="container404">
    <div class="block404">
      <img src="../../images/404left.png" alt="" class="artist404">
    </div>
    <div class="block404">
      <img src="../../images/logo.svg" alt="" class="logo404">
      <p>404</p>
      <span>К сожалению, нам не удалось найти запрашиваемую вами страницу</span>
      <div class="buttons404">
        <a href="/" class="form-button form-button_secondary">Главная</a>
        <a href="javascript: void(0)" class="form-button form-button_secondary">Карта сайта</a>
      </div>
    </div>
    <div class="block404">
      <img src="../../images/404right.png" alt="" class="artist404">
    </div>
  </div>
</template>
<script>
  export default {
    created() {
      this.$store.commit('appColumns/set404', true);
    },
    destroyed() {
      this.$store.commit('appColumns/set404', false);
    }
  }
</script>
<style scoped lang="scss">
  @import '~scss/_variables.scss';

  .container404 {
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: space-around;
    align-items: center;

    & .block404 {
      width: calc(100% / 3);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      max-height: 75%;
      height: 100%;

      &:first-child {
        margin-bottom: 10%;
      }

      &:last-child {
        margin-top: 10%;
      }

      & .artist404 {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center center;
      }

      & .logo404 {
        width: 68px;
        height: 60px;
        margin-bottom: 10%;
      }

      & p {
        font-size: 200px;
        color: #000;
        text-align: center;
        margin-bottom: 16px;
        margin-top: 0;
      }

      & span {
        font-size: 20px;
        line-height: 32px;
        color: #000;
        max-width: 350px;
        text-align: center;
        margin-bottom: 5%;
      }

      & .buttons404 {
        width: 100%;
        max-width: 350px;
        display: flex;
        justify-content: space-between;

        & .form-button {
          display: -webkit-flex;
          display: -ms-flex;
          display: flex;
          -ms-align-items: center;
          align-items: center;
          justify-content: center;
          width: calc(50% - 8px);
          box-sizing: border-box;
          font-family: 'Gotham Pro', sans-serif;
          font-size: 14px;
          position: relative;
          border-radius: $input_border_radius;
          cursor: pointer;
          user-select: none;
          transition: color .2s;

          &_secondary {
            color: #231f20;
            padding: 16px 14px 16px 14px;
            border: 1px solid $red-violet;

            &:hover {
              color: white;
              padding: 17px 15px 17px 15px;
              border: none;
              background: $gradient-linear;

              .form-button__loader::v-deep div:after {
                background: white !important;
              }
            }

            .form-button__loader::v-deep div:after {
              background: #999 !important;
            }
          }
        }
      }
    }
  }
</style>
