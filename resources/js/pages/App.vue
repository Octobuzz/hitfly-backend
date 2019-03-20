<template>
  <div id="main">
    <!--<Header /> TODO: temp-->
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
