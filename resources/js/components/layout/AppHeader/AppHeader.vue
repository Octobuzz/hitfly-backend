<template>
  <header :class="['head', $attrs.class]">
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

      <!--      <router-link-->
      <!--        to="/sets"-->
      <!--        :class="[-->
      <!--          'button',-->
      <!--          'head-nav-item',-->
      <!--          { active: path === '/sets' }-->
      <!--        ]"-->
      <!--      >-->
      <!--        Подборки-->
      <!--      </router-link>-->

      <!--      <router-link-->
      <!--        v-if="isAuthenticated"-->
      <!--        to="/life-hacks"-->
      <!--        :class="[-->
      <!--          'button',-->
      <!--          'head-nav-item',-->
      <!--          { active: path.startsWith('/life-hacks') }-->
      <!--        ]"-->
      <!--      >-->
      <!--        Лайфхаки-->
      <!--      </router-link>-->

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

      <a
        href="http://2rockmusic.com"
        target="_blank"
        rel="noopener noreferrer nofollow"
        :class="[
          'button',
          'head-nav-item'
        ]"
      >
        О cтудии
      </a>
    </nav>

    <div class="head__right">
      <UnauthenticatedPopoverWrapper>
        <template #auth-content>
          <a
            v-if="ableToPerform || roles.includes('listener')"
            class="button gradient head-right-item head-right-item_upload"
            @click="toUpload"
          >
            Загрузить музыку
          </a>
        </template>

        <template #unauth-popover-trigger>
          <button class="button gradient head-right-item head-right-item_upload">
            Загрузить музыку
          </button>
        </template>
      </UnauthenticatedPopoverWrapper>

      <!--      <span class="head-right-item app-header__loupe-icon">-->
      <!--        <IconButton class="head-right-item__button">-->
      <!--          <LoupeIcon />-->
      <!--        </IconButton>-->
      <!--      </span>-->

      <span class="head-right-item">
        <UnauthenticatedPopoverWrapper>
          <template #auth-content>
            <NotificationButtonWithPopover />
          </template>

          <template #unauth-popover-trigger>
            <IconButton class="block">
              <BellIcon />
            </IconButton>
          </template>
        </UnauthenticatedPopoverWrapper>
      </span>

      <span class="app-header__right-item">
        <UnauthenticatedPopoverWrapper>
          <template #auth-content>
            <HeaderProfilePopover>
              <img
                class="app-header__right-item app-header__profile-image block"
                :src="myProfile.avatar || anonymousAvatar"
                alt="User avatar"
              >
            </HeaderProfilePopover>
          </template>

          <template #unauth-popover-trigger>
            <img
              class="app-header__right-item app-header__profile-image block"
              :src="myProfile.avatar || anonymousAvatar"
              alt="User avatar"
            >
          </template>
        </UnauthenticatedPopoverWrapper>
      </span>
    </div>

    <div class="drop-menu">
      <div class="drop-menu-list">
        <div class="drop-menu-list__item drop-menu-list__item_upload">
          <a class="button gradient drop-menu-list__button-upload" @click="toUpload">
            Загрузить музыку
          </a>
        </div>
        <a class="drop-menu-list__item" @click="goToPage('/bonus-program')">
          <span>
            Бонусная программа
          </span>
        </a>

        <!--        Commented button should be available in next release-->
        <!--        <router-link to="/" class="drop-menu-list__item">-->
        <!--          <span>-->
        <!--            Мои покупки-->
        <!--          </span>-->
        <!--        </router-link>-->

        <!--        Commented button should be available in next release-->
        <!--        <router-link to="/" class="drop-menu-list__item">-->
        <!--          <span>-->
        <!--            Истории победителей-->
        <!--          </span>-->
        <!--        </router-link>-->

        <a class="drop-menu-list__item" @click="goToPage('/reviews')">
          <span>
            Звёздные эксперты
          </span>
        </a>
        <a
          v-if="isAuthenticated"
          class="drop-menu-list__item"
          @click="goToPage('/profile/edit')"
        >
          <span>
            Настройки
          </span>
        </a>

        <!--        Commented button should be available in next release-->
        <!--        <a to="/" class="drop-menu-list__item">-->
        <!--          <span>-->
        <!--            Форма обратной связи-->
        <!--          </span>-->
        <!--        </a>-->

        <a class="drop-menu-list__item" @click="goToPage('/contacts')">
          <span>
            Контакты
          </span>
        </a>
      </div>
    </div>
  </header>
</template>

<script>
import { mapGetters } from 'vuex';
import logo from 'images/logo.svg';
import anonymousAvatar from 'images/anonymous-avatar.png';
import IconButton from 'components/IconButton.vue';
import LoupeIcon from 'components/icons/LoupeIcon.vue';
import BellIcon from 'components/icons/BellIcon.vue';
import NotificationButtonWithPopover from 'components/notifications/NotificationButtonWithPopover';
import HeaderProfilePopover from 'pages/profile/HeaderProfilePopover';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import gql from './gql';

export default {
  components: {
    IconButton,
    LoupeIcon,
    BellIcon,
    NotificationButtonWithPopover,
    HeaderProfilePopover,
    UnauthenticatedPopoverWrapper
  },

  data() {
    return {
      logo,
      anonymousAvatar,
      myProfile: {
        avatar: ''
      }
    };
  },

  computed: {
    path() {
      return this.$route.fullPath;
    },

    ...mapGetters({
      isAuthenticated: 'isAuthenticated',
      apolloClient: 'apolloClient',
      ableToPerform: 'profile/ableToPerform',
      ableToComment: 'profile/ableToComment',
      roles: 'profile/roles'
    })
  },

  mounted() {
    document.dispatchEvent(new Event('jquery-side-menu-update'));
  },

  methods: {
    goToProfilePage() {
      if (!this.isAuthenticated) return;

      if (this.ableToPerform) {
        this.$router.push('/profile/my-music');

        return;
      }

      if (this.ableToComment) {
        this.$router.push('/profile/my-reviews');
      }
    },

    toUpload() {
      if (this.isAuthenticated) {
        window.location = '/upload';
      } else {
        window.location = '/login';
      }
    },

    goToPage(location) {
      window.location = location;
    }
  },

  apollo: {
    myProfile() {
      return {
        query: gql.query.MY_PROFILE,
        update({ myProfile }) {
          const avatar = myProfile.avatar
            .filter(av => av.size === 'size_56x56')[0].url;

          return {
            avatar
          };
        },
        error(err) {
          console.dir(err);
        },
        skip() {
          return !this.isAuthenticated;
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../../../../sass/app.scss"
/>

<style scoped>
.app-header__loupe-icon::v-deep svg {
  fill: transparent !important;
}

.app-header__right-item {
  height: 40px;
}

.app-header__profile-image {
  border-radius: 50%;
  cursor: pointer;
}

.block {
  display: block;
}
</style>
