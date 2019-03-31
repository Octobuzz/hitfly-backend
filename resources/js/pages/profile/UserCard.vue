<template>
  <div class="profileAside">
    <div class="profileGeneral profileAsideBlock">
      <!--TODO: add transition and/or loader for all fetching data-->
      <img
        class="profileGeneral__img"
        :src="user.avatar"
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
        <p class="placeMark">
          {{ user.location }}
        </p>
      </div>

      <IconButton
        v-if="!isEditingUser"
        class-name="editButton"
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
        <router-link
          v-if="!isCreatingGroup"
          to="/profile/create-group"
        >
          <button class="profileAsideButton">
            Создать группу
          </button>
        </router-link>
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
              :src="group.avatar"
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
              class-name="editButton"
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
import IconButton from '../../sharedComponents/IconButton.vue';
import PencilIcon from '../../sharedComponents/icons/PencilIcon.vue';

const IS_LOADING = 'Is loading...';

// TODO: gql loader

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
        avatar: '/images/acc.jpg',
        followerCount: IS_LOADING,
        location: IS_LOADING,
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
      isFetching: true
    };
  },
  computed: {
    isCreatingGroup() {
      return this.$route.fullPath === '/profile/create-group';
    },
    isUpdatingGroup(groupId) {
      // TODO: get id from store

      return false;
    },
    isEditingUser() {
      return this.$route.fullPath === '/profile/edit';
    }
  },
  methods: {

    goToEditUser() {
      this.editUserMode = true;
    },
    goToUpdateGroup(groupId) {
      this.editGroupMode = groupId;
    },
    goToCreateGroup() {
      this.editGroupMode = null;
    }
  },
  apollo: {
    users: {
      // query: gql`
      //   query GetUser {
      //     users(email: "test@test.mail") {
      //       username
      //       followersCount
      //       location
      //       musicGroups {
      //         id
      //         name
      //         avatarGroup
      //         followerCount
      //       },
      //       watchList {
      //         __typename
      //         id
      //         ... on User {
      //           username
      //           location
      //         }
      //         ... on MusicGroup {
      //           name
      //           primaryFunction
      //         }
      //       }
      //     }
      //   }
      // `,
      query: gql`
        query GetUser {
          users(email: "test@test.mail") {
            username
            followersCount
            musicGroups {
              id
              name
              avatarGroup
            }
          }
        }
      `,
      update(data) {
        return data[0];
      },
      result({ data, loading }) {
        if (loading === false) {
          this.isFetching = false;

          // TODO: store user id

          return data;
        }
      },
      error(error) {
        // TODO: implement error
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
