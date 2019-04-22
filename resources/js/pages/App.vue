<template>
  <div id="main">
    <flash-message transition-name="flash" />
    <Header />
    <router-view />
    <Footer />
  </div>
</template>

<script>
import Header from './Header.vue';
import Footer from './Footer.vue';

export default {
  components: {
    Header,
    Footer
  },
  data() {
    return {};
  }
};
</script>

<style lang="scss">
.flash__message {
  box-sizing: border-box;
  font-family: "Gotham Pro", serif;
  font-size: 14px;
  color: white;
  position: fixed;
  top: 10%;
  left: 50%;
  padding: 15px 60px 15px 15px;
  margin-bottom: 10px;
  transform: translate(-50%);
  border-radius: 3px;
  z-index: 2000;
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
  transform: rotateX(-30deg) scale(.88) translateY(-30px);
}

.flash-leave-active {
  position: absolute;
}
</style>
