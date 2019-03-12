<template>
  <div id="main">
    <header-bar></header-bar>
    <router-view></router-view>
    <footer-bar></footer-bar>
  </div>
</template>
<script>
import HeaderBar from './components/HeaderBar.vue'
import FooterBar from './components/FooterBar.vue'

  export default{
    data(){
      return{

      }
    },
    components:{
      HeaderBar, FooterBar
    }
  }
</script>
