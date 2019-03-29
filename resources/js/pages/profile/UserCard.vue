<template>
  <div class="profileAside">
    <div class="profileGeneral profileAsideBlock">
      <!--TODO: add transition and/or loader for all fetching data-->
      <span v-if="editUserMode">edit mode</span>

      <img
        class="profileGeneral__img"
        :src="user.avatar"
        alt="User avatar"
      >

      <div class="profileGeneral__info">
        <!--TODO: check h1->h2->h3-->
        <h3>
          {{ user.firstName }} {{ user.lastName }}
        </h3>
        <p class="profileGeneral__subheader">
          {{ user.followerCount }}
        </p>
        <p class="placeMark">
          {{ user.location }}
        </p>
      </div>

      <EditButton @press="enterEditUserMode" />
    </div>

    <div class="profileGroups profileAsideBlock">
      <div class="profileAsideBlock__heading">
        <h4>
          Мои группы
        </h4>
        <button class="profileAsideButton">
          <!--TODO: create group-->
          Создать группу
        </button>
      </div>

      <div class="profileGroups__wrapper">
        <div
          v-for="group in user.myGroups"
          :key="group.id"
          class="profileGroup"
        >
          <div class="profileGroup__wrapper">
            <span v-if="editGroupMode === group.id">edit mode</span>

            <img
              class="profileGeneral__img profileAsideBlock__img"
              :src="group.avatar"
              alt="Group avatar"
            >

            <div class="profileGroup__body">
              <div class="profileAsideBlock__text">
                <p>
                  {{ group.name }}
                </p>
                <span>
                  {{ group.followerCount }} подписчиков
                </span>
              </div>
            </div>
            <EditButton @press="enterEditGroupMode(group.id)" />
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
              {{ userOrGroup.firstName }}
              {{ userOrGroup.lastName }}
            </p>
            <span class="placeMark placeMark-small">
              {{ userOrGroup.location }}
            </span>
          </div>
        </div>
      </div>
    </div>

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
import gql from 'graphql-tag';
import EditButton from './EditButton.vue';

const IS_LOADING = 'Is loading...';

// TODO: gql loader

export default {
  components: {
    EditButton
  },
  data() {
    return {
      user: {
        username: IS_LOADING,
        firstName: 'Андрей',
        lastName: 'Кондоров',
        avatar: '/images/acc.jpg',
        followerCount: IS_LOADING,
        location: 'Ульяновск',
        level: 'новичок',
        myGroups: [
          {
            id: 0,
            name: 'Название группы',
            avatar: '/images/acc.jpg',
            followerCount: 32000
          },
          {
            id: 1,
            name: 'Название группы',
            avatar: '/images/acc.jpg',
            followerCount: 32000
          }
        ],
        watchList: [
          {
            firstName: 'Antonio',
            lastName: 'Rodrigues Jr',
            avatar: '/images/acc.jpg',
            location: 'Москва',
          },
          {
            id: 0,
            name: 'Honey monday',
            avatar: '/images/acc.jpg',
            followerCount: 32000,
            location: 'Москва'
          },
          {
            firstName: 'Кирилл',
            lastName: 'Михайлов',
            avatar: '/images/acc.jpg',
            location: 'Калининград',
          }
        ]
      },
      editUserMode: false,
      editGroupMode: null
    };
  },
  methods: {
    enterEditUserMode() {
      this.editUserMode = true;
    },
    leaveEditUserMode() {
      this.editUserMode = false;
    },
    enterEditGroupMode(groupId) {
      this.editGroupMode = groupId;
    },
    leaveEditGroupMode() {
      this.editGroupMode = null;
    }
  },
  apollo: {
    users: {
      query: gql`
        query GetUser {
          users(email: "test@test.mail") {
            username
          }
        }
      `,
      update(data) {
        console.log('server response from "users" query', data);
      },
      error(error) {
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
