<template>
  <header class="head">
      <div class="head__left">
          <span class="menu-call is-close"> <!-- is-close / is-show -->
              <span class="menu-call__rows"></span>
          </span>
          <a class="head__logo" href="">
              <img src="/images/logo.svg" alt="logo">
          </a>
      </div>
      <nav class="head__nav">
          <a class="button head-nav-item active" href="/">Главная</a>
          <a class="button head-nav-item g-border" href="/">Подборка</a>
          <a class="button head-nav-item" href="/">Stream</a>
          <a class="button head-nav-item" href="/">Блог</a>
          <a class="button head-nav-item" href="/">О нас</a>
      </nav>
      <div class="head__right">
          <a href="" class="button gradient head-right-item head-right-item_upload">Загрузить музыку</a>
          <button class="button-icon head-right-item">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="icon-search" viewBox="0 0 19 20"><defs><path id="a" d="M1268 44a8 8 0 1 1 16 0 8 8 0 0 1-16 0z"/><path id="c" d="M1281.5 50l4.053 4.053"/><clipPath id="b"><use fill="#fff" xlink:href="#a"/></clipPath></defs><use fill="#fff" fill-opacity="0" stroke="#231f20" stroke-miterlimit="50" stroke-width="6" clip-path="url(&quot;#b&quot;)" xlink:href="#a" transform="translate(-1268 -36)"/><use fill="#fff" fill-opacity="0" stroke="#231f20" stroke-linecap="round" stroke-miterlimit="50" stroke-width="3" xlink:href="#c" transform="translate(-1268 -36)"/></svg>
          </button>
          <button class="button-icon head-right-item">
              <span class="button-icon__tips">4</span>
              <svg viewBox="0 0 16 21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M14 9.7546053V6.0263158c0-2.57236843-1.6733333-4.76052633-4-5.57565791C9.40177778.19572368 8.73511111.06825658 8 .06825658c-.73511111 0-1.40177778.1274671-2 .38240131-2.32666667.81578948-4 3.00394737-4 5.57960531v3.7236842L.86230604 11.2512873C.30287472 11.9875652 0 12.886814 0 13.8115132c0 .7871874.6381415 1.4253289 1.42532895 1.4253289h3.24133772C4.66666667 17.0506579 6.162 18.5263158 8 18.5263158c1.838 0 3.3333333-1.4756579 3.3333333-3.2894737h3.2413378c.7871874 0 1.4253289-.6381415 1.4253289-1.4253289 0-.9246896-.3029285-1.8239192-.8624427-2.560122L14 9.7546053z" fill="#231F20" fill-rule="nonzero"/></g></svg>
          </button>
          <img class="head__profile head-right-item" src="/images/test_ava.jpg" alt="logo">
      </div>
      <div class="drop-menu"> <!-- is-close / is-show -->
          <div class="drop-menu-list">
              <div class="drop-menu-list__item drop-menu-list__item_upload">
                  <a class="button drop-menu-list__button-upload">Загрузить музыку</a>
              </div>
              <a class="drop-menu-list__item" href="/">
                  <span>О проекте</span>
              </a>
              <a class="drop-menu-list__item" href="/">
                  <span>Вопросы и ответы</span>
              </a>
              <a class="drop-menu-list__item" href="/">
                  <span>Полезные статьи</span>
              </a>
              <a class="drop-menu-list__item" href="/">
                  <span>Истории победителей</span>
              </a>
              <a class="drop-menu-list__item" href="/">
                  <span>Звезды</span>
              </a>
              <a class="drop-menu-list__item" href="/">
                  <span>Настройки</span>
              </a>
          </div>
      </div>
  </header>
</template>
<script>
  export default{
    data(){
      return{

      }
    },
  }
</script>
<styles lang="sass" scoped>

</styles>
