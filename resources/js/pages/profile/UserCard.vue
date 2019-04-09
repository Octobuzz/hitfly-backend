<template>
  <div class="profileAside">
    <div class="profileGeneral profileAsideBlock">
      <!--TODO: add transition and/or loader for all fetching data-->
      <img
        class="profileGeneral__img"
        :src="user.avatar || '/images/anonymous-avatar.png'"
        alt="User avatar"
      >

      <div class="profileGeneral__info">
        <!--TODO: check h1->h2->h3-->
        <h3>
          {{ user.username }}
        </h3>
        <p class="profileGeneral__subheader">
          {{ user.followersCount }}
        </p>
        <p
          v-if="user.location && user.location.title"
          class="placeMark"
        >
          {{ user.location.title }}
        </p>
      </div>

      <IconButton
        v-if="!isEditingUser"
        class="editButton"
        @press="goToEditUser"
      >
        <PencilIcon/>
      </IconButton>
    </div>

    <div class="profileGroups profileAsideBlock">
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
          v-for="group in user.musicGroups"
          :key="group.id"
          class="profileGroup"
        >
          <div class="profileGroup__wrapper">
            <img
              class="profileGeneral__img profileAsideBlock__img"
              :src="group.avatarGroup || '/images/anonymous-avatar.png'"
              alt="Group avatar"
            >

            <div class="profileGroup__body">
              <div class="profileAsideBlock__text">
                <p>
                  {{ group.name }}
                </p>
                <span>
                  {{ group.followersCount }} подписчиков
                </span>
              </div>
            </div>

            <IconButton
              v-if="!isUpdatingGroup(group.id)"
              class="editButton"
              @press="goToUpdateGroup(group.id)"
            >
              <PencilIcon/>
            </IconButton>
          </div>
        </div>
      </div>
    </div>

    <div class="profileFollow profileAsideBlock">
      <div class="profileAsideBlock__heading">
        <h4>
          Слежу
        </h4>
        <!--TODO: clarify design and functionality for 'все'-->
        <button class="profileAsideButton">
          Все
        </button>
      </div>

      <div class="profileFollow__wrapper">
        <div
          v-for="userOrGroup in user.watchList"
          :key="userOrGroup.id"
          class="profileGroup__wrapper"
        >
          <img
            class="profileGeneral__img profileAsideBlock__img"
            :src="userOrGroup.avatar"
            alt="User or Group avatar"
          >
          <div class="profileAsideBlock__text">
            <p>
              {{ userOrGroup.name }}
            </p>
            <span class="placeMark placeMark-small">
              {{ userOrGroup.location }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!--TODO: extract user status into separate component-->

    <div class="profileStats profileAsideBlock">
      <div class="profileAsideBlock__heading">
        <!--TODO: add user level to class map-->
        <h4 class="userType novice">
          {{ user.level }}
        </h4>
        <button class="profileAsideButton">
          <!--TODO: clarify-->
          Подробнее
        </button>
        <div class="profileStats__subheading">
          <p>
            до
            <span class="userType userType-small fan" />
            Любителя осталось:
            <span>
              3006
            </span>
          </p>
        </div>
      </div>

      <!--TODO: integrate user card footer-->
      <div class="profileStatsItems">
        <div class="profileStatsItem">
          <img class="profileStatsItem__icon" src="/images/profileIcon1.png" />
          <p class="profileStatsItem__text">1 345 <br>бонусов</p>
        </div>
        <div class="profileStatsItem">
          <img class="profileStatsItem__icon" src="/images/profileIcon2.png" />
          <p class="profileStatsItem__text">128 дней <br>в Digico</p>
        </div>
        <div class="profileStatsItem">
          <img class="profileStatsItem__icon" src="/images/profileIcon3.png" />
          <p class="profileStatsItem__text">10 896 <br>любимых песен</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import gql from './gql';
import IconButton from '../../sharedComponents/IconButton.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';

const IS_LOADING = 'Is loading...';

export default {
  components: {
    IconButton,
    PencilIcon
  },

  data() {
    return {
      user: {
        username: IS_LOADING,
        name: IS_LOADING,
        avatar: IS_LOADING,
        followersCount: IS_LOADING,
        location: {
          title: IS_LOADING
        },
        level: 'новичок',
        musicGroups: [],
        watchingList: []
      },
      isFetching: true
    };
  },

  computed: {
    isCreatingGroup() {
      return this.$route.fullPath === '/profile/create-group';
    },
    isEditingUser() {
      return this.$route.fullPath === '/profile/edit';
    }
  },

  methods: {
    isUpdatingGroup(groupId) {
      return this.$store.getters.editGroupId === groupId;
    },
    goToEditUser() {
      this.$router.push('/profile/edit');
    },
    goToUpdateGroup(groupId) {
      this.$store.commit('setEditGroupId', groupId);
      this.$router.push('/profile/update-group');
    },
    goToCreateGroup() {
      this.$router.push('/profile/create-group');
    }
  },

  apollo: {
    user: {
      query: gql.query.MY_PROFILE,
      update(data) {
        return data[0];
      },
      result({ data: { user }, loading }) {
        if (loading === false) {
          this.isFetching = false;
          this.user = user;
        }
      },
      error(error) {
        // TODO: implement error handling
        console.log(error);
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
