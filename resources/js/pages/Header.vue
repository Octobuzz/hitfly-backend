<template>
  <header class="head">
    <div class="head__left">
      <span class="menu-call is-close">
        <span class="menu-call__rows" />
      </span>
      <router-link to="/" class="head__logo">
        <img :src="logo" alt="logo">
      </router-link>
    </div>

    <nav class="head__nav">
      <router-link
        to="/"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/' }
        ]"
      >
        Главная
      </router-link>
      <router-link
        to="/"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/sets' }
        ]"
      >
        Подборки
      </router-link>
      <router-link
        to="/"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/blogs' }
        ]"
      >
        Блог
      </router-link>
      <router-link
        to="/about"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/about' }
        ]"
      >
        О нас
      </router-link>
    </nav>

    <div class="head__right">
      <router-link
        to="/upload"
        class="button gradient head-right-item head-right-item_upload"
      >
        Загрузить музыку
      </router-link>

      <span class="head-right-item">
        <IconButton>
          <LoupeIcon />
        </IconButton>
      </span>

      <span class="head-right-item">
        <span class="button-icon__tips">4</span>
        <IconButton>
          <BellIcon />
        </IconButton>
      </span>

      <img
        class="head__profile head-right-item"
        :src="anonymousAvatar"
        alt="User avatar"
        @click="goToProfilePage"
      >
    </div>

    <div class="drop-menu">
      <div class="drop-menu-list">
        <div class="drop-menu-list__item drop-menu-list__item_upload">
          <router-link to="/upload" class="button drop-menu-list__button-upload">
            <img :src="logo" alt="logo">
          </router-link>
        </div>
        <router-link to="/profile/bonus-program" class="drop-menu-list__item">
          <span>
            Бонусная программа
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Мои покупки
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Вопросы и ответы
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Истории победителей
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Звезды
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Настройки
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Форма обратной связи
          </span>
        </router-link>
      </div>
    </div>
  </header>
</template>

<script>
import logo from 'images/logo.svg';
import anonymousAvatar from 'images/anonymous-avatar.png';
import IconButton from 'components/IconButton.vue';
import LoupeIcon from 'components/icons/LoupeIcon.vue';
import BellIcon from 'components/icons/BellIcon.vue';

export default {
  components: {
    IconButton,
    LoupeIcon,
    BellIcon
  },

  data() {
    return {
      logo,
      anonymousAvatar
    };
  },

  computed: {
    path() {
      return this.$route.fullPath;
    }
  },

  methods: {
    goToProfilePage() {
      this.$router.push('/profile/my-music');
    }
  },

  apollo: {

  }
};
</script>

<style
  scoped
  lang="scss"
  src="../../sass/app.scss"
/>
