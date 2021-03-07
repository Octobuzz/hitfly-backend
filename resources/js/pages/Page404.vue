<template>
  <div class="container404">
    <div class="block404">
      <img
        src="../../images/404left.png"
        alt=""
        class="artist404"
      >
    </div>
    <div class="block404">
      <img
        src="../../images/logo.svg"
        alt=""
        class="logo404"
      >
      <p>404</p>
      <span>К сожалению, нам не удалось найти запрашиваемую вами страницу</span>
      <div class="buttons404">
        <a href="/" class="form-button form-button_secondary">Главная</a>
      </div>
    </div>
    <div class="block404">
      <img
        src="../../images/404right.png"
        alt=""
        class="artist404"
      >
    </div>
  </div>
</template>
<script>
export default {

};
</script>
<style scoped lang="scss">
  @import '~scss/_variables.scss';

  .container404 {
    padding: 60px 30px 0;

    & .block404 {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    & .logo404 {
      margin-bottom: 60px;
    }

    & p {
      font-size: 100px;
      color: #000;
      text-align: center;
      font-weight: 700;
      margin-bottom: 8px;
      margin-top: 0;
    }

    & span {
      font-size: 16px;
      line-height: 28px;
      color: #000;
      max-width: 350px;
      text-align: center;
      margin-bottom: 60px;
    }

    & .artist404 {
      display: none;
    }

    & .buttons404 {
      display: flex;
      justify-content: center;
      margin: 0 auto;
      width: 50%;
    }

    & .form-button {
      width: 100%;
      box-sizing: border-box;
      font-size: 14px;
      color: #000;
      text-align: center;
      position: relative;
      border-radius: $input_border_radius;
      cursor: pointer;
      user-select: none;
      transition: color .2s;

      &_secondary {
        color: #231f20;
        padding: 14px 7px;
        border: 1px solid $red-violet;

        &:hover {
          color: white;
          padding: 15px 8px;
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

  @media (min-width: 750px) {
     .container404 {
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: space-around;
        align-items: center;

      & .block404 {
          width: calc(100% / 3);
          max-height: 75%;
          height: 100%;

          &:first-child {
            margin-bottom: 10%;
          }

          &:last-child {
            margin-top: 10%;
          }

        & .artist404 {
          display: inline;
          width: 100%;
          height: 100%;
          object-fit: contain;
          object-position: center center;
        }

        & .logo404 {
          display: inline;
          width: 68px;
          height: 60px;
          margin-bottom: 10%;
        }

        & p {
          font-size: 200px;
          margin-bottom: 16px;
        }

        & span {
          font-size: 20px;
          line-height: 32px;
          margin-bottom: 5%;
        }

        & .buttons404 {
          width: 100%;

          & .form-button {
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            -ms-align-items: center;
            align-items: center;
            justify-content: center;
            width: calc(50% - 8px);
            margin: 0 auto;

            &_secondary {
              padding: 16px 14px;

              &:hover {
                padding: 17px 15px 17px 15px;
              }
            }
        }
      }
    }
  }
  }

</style>
