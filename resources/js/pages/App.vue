<template>
  <div id="main">
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
