<template>
  <div>
    <div v-show="dataInitialized" class="profileAside">
      <div class="profileGeneral profileAsideBlock">
        <img
          class="profileGeneral__img"
          :src="myProfile.avatar || anonymousAvatar"
          alt="User avatar"
        >

        <div class="profileGeneral__info">
          <!--TODO: check h1->h2->h3-->
          <h3>
            {{ myProfile.name }}
          </h3>
          <p class="profileGeneral__subheader">
            {{ myProfile.followersCount || '0' }} подписчиков
          </p>

          <p
            v-if="myProfile.location"
            class="placeMark"
          >
            {{ myProfile.location.title }}
          </p>
        </div>

        <IconButton
          v-if="!isEditingProfile"
          class="profileGeneral__editButton"
          @press="goToEditProfile"
        >
          <PencilIcon/>
        </IconButton>
      </div>

      <div
        v-if="myProfile.musicGroups.length === 0"
        class="profileAsideBlock"
      >
        <router-link
          to="/profile/create-group"
          :class="[
            'profileCreateGroup',
            {
              hover: $route.fullPath === '/profile/create-group'
            }
          ]"
        >
          Создать свою группу
          <span class="profileCreateGroup__icon">
            <ArrowIcon/>
          </span>
        </router-link>
      </div>

      <div v-else class="profileGroups profileAsideBlock">
        <div class="profileAsideBlock__heading">
          <h4>
            Мои группы
          </h4>
          <button
            v-if="!isCreatingGroup"
            class="profileAsideButton"
            @click="goToCreateGroup"
          >
            Создать группу
          </button>
        </div>

        <div class="profileGroups__wrapper">
          <div
            v-for="group in myProfile.musicGroups.slice(0, 3)"
            :key="group.id"
            class="profileGroup"
          >
            <div class="profileGroup__wrapper">
              <img
                class="profileGeneral__img profileAsideBlock__img"
                :src="
                  group.avatarGroup.filter(
                    avatar => avatar.size === 'size_40x40'
                  )[0].url || anonymousAvatar
                "
                alt="Group avatar"
              >

              <div class="profileGroup__body">
                <div class="profileAsideBlock__text">
                  <p>
                    {{ group.name }}
                  </p>
                  <span>
                    {{ group.followersCount || '0' }} подписчиков
                  </span>
                </div>
              </div>

              <IconButton
                v-if="!isUpdatingGroup(group.id)"
                class="profileGeneral__editButton"
                @press="goToUpdateGroup(group.id)"
              >
                <PencilIcon/>
              </IconButton>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="
          myProfile.watchingUser.length > 0
            || myProfile.watchingMusicGroup.length > 0
        "
        class="profileFollow profileAsideBlock"
      >
        <div class="profileAsideBlock__heading">
          <h4>
            Слежу
          </h4>
          <button class="profileAsideButton">
            Все
          </button>
        </div>

        <div
          v-if="myProfile.watchingUser.length > 0"
          class="profileFollow__wrapper"
        >
          <span class="profileFollow__subsection-header">
            Пользователи
          </span>
          <div
            v-for="user in myProfile.watchingUser.slice(0, 3)"
            :key="user.id"
            class="profileGroup__wrapper"
          >
            <img
              class="profileGeneral__img profileAsideBlock__img"
              :src="
                user.avatar.filter(
                  image => image.size === 'size_56x56'
                )[0].url || anonymousAvatar"
              alt="User avatar"
            >
            <div class="profileAsideBlock__text">
              <p>
                {{ user.username }}
              </p>
              <span
                v-if="user.location"
                class="placeMark placeMark-small"
              >
                {{ user.location.title }}
              </span>
            </div>
          </div>
        </div>

        <div
          v-if="myProfile.watchingMusicGroup.length > 0"
          class="profileFollow__wrapper"
        >
          <span class="profileFollow__subsection-header">
            Группы
          </span>
          <div
            v-for="group in myProfile.watchingMusicGroup.slice(0, 3)"
            :key="group.id"
            class="profileGroup__wrapper"
          >
            <img
              class="profileGeneral__img profileAsideBlock__img"
              :src="
                group.avatarGroup.filter(
                  image => image.size === 'size_40x40'
                )[0].url || anonymousAvatar"
              alt="Group avatar"
            >
            <div class="profileAsideBlock__text">
              <p>
                {{ group.name }}
              </p>
              <span
                v-if="group.location"
                class="placeMark placeMark-small"
              >
                {{ group.location.title }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="myProfile.activity"
        class="profileAboutMe profileAsideBlock"
      >
        <div class="profileAsideBlock__heading profileAboutMe__heading">
          <h4>
            Обо мне
          </h4>
        </div>
        <div class="profileAsideBlock__text profileAboutMe__text">
          <p>
            {{ myProfile.activity }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import IconButton from 'components/IconButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import ArrowIcon from 'components/icons/ArrowIcon.vue';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    IconButton,
    PencilIcon,
    ArrowIcon
  },

  data() {
    return {
      myProfile: {
        avatar: '',
        name: '',
        location: '',
        followersCount: '',
        activity: '',
        musicGroups: [],
        watchingUser: [],
        watchingMusicGroup: []
      },
      dataInitialized: false,
      anonymousAvatar
    };
  },

  computed: {
    isCreatingGroup() {
      return this.$route.fullPath === '/profile/create-group';
    },
    isEditingProfile() {
      return this.$route.fullPath === '/profile/edit';
    },
    editGroupId() {
      return this.$store.getters['profile/editGroupId'];
    }
  },

  methods: {
    isUpdatingGroup(groupId) {
      return this.editGroupId === groupId;
    },

    goToEditProfile() {
      this.$router.push('/profile/edit');
    },

    goToUpdateGroup(groupId) {
      this.$store.commit('profile/setCustomRedirect', true);
      this.setEditGroupId(groupId);

      if (this.$route.fullPath !== '/profile/update-group') {
        this.$router.push('/profile/update-group');

        return;
      }
      this.$router.customData.navHistory.unshift(this.$route);
    },

    goToCreateGroup() {
      this.$router.push('/profile/create-group');
    },

    setEditGroupId(id) {
      this.$store.commit('profile/setEditGroupId', { id });
    }
  },

  apollo: {
    userProfile: {
      query: gql.query.MY_PROFILE,
      update({ myProfile }) {
        const {
          avatar,
          username,
          location,
          description,
          musicGroups,
          watchingUser,
          watchingMusicGroup,
          roles
        } = myProfile;

        this.myProfile.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.myProfile.name = username;
        this.myProfile.musicGroups = musicGroups;
        this.myProfile.watchingUser = watchingUser;
        this.myProfile.watchingMusicGroup = watchingMusicGroup;

        if (location && location.title) {
          this.myProfile.location = location.title;
        }

        if (roles.some(role => role === 'Артист')) {
          if (description) {
            this.myProfile.activity = description;
          }
        }
      },
      result() {
        this.dataInitialized = true;
      },
      error(err) {
        console.log(err);
      }
    },
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./UserCard.sass"
/>
