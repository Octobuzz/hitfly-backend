<template>
  <div id="main">
    <IconGradientRadial/>
    <flash-message
      class="flashpool"
      transition-name="flash"
    />
    <Header />
    что-то странное
    <router-view />
    <Footer />
  </div>
</template>

<script>
import IconGradientRadial from 'components/IconGradientRadial.vue';
import Header from './Header.vue';
import Footer from './Footer.vue';

export default {
  components: {
    IconGradientRadial,
    Header,
    Footer
  },
  data() {
    return {};
  }
};
</script>

<style scoped lang="scss">
.flashpool::v-deep {
  box-sizing: border-box;
  display: flex;
  align-items: center;
  flex-direction: column;
  position: fixed;
  max-height: 400px;
  top: 10%;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2000;

  .flash__message {
    box-sizing: border-box;
    font-family: "Gotham Pro", serif;
    font-size: 14px;
    color: white;
    position: relative;
    padding: 15px 60px 15px 15px;
    margin-bottom: 10px;
    border-radius: 3px;
    transition: opacity .4s, transform .4s;
    background: #313131;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }

  .flash__message.success {

  }

  .flash__message.warning {

  }

  .flash__message.error {

  }

  .flash__message.info {

  }

  .flash__close-button {
    box-sizing: border-box;
    font-size: 18px;
    line-height: 10px;
    color: white;
    position: absolute;
    top: 50%;
    right: 17px;
    margin: 0;
    padding: 8px;
    transform: translateY(-50%);
    background: transparent;
    cursor: pointer;
  }

  .flash__close-button:active,
  .flash__close-button:focus {
    outline: none;
  }

  .flash-enter,
  .flash-leave-to {
    opacity: 0;
    transform: translateX(-20px);
  }
}

@media screen and (max-width: 767px) {
  .flashpool {
    min-width: 260px;
  }
}

</style>
