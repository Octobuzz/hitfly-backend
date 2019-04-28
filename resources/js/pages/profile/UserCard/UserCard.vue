<template>
  <div class="profileAside">
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
          {{ myProfile.location }}
        </p>
      </div>

      <IconButton
        v-if="!isEditingProfile"
        class="editButton"
        @press="goToEditProfile"
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
          v-for="group in myProfile.musicGroups"
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
              @press="goToUpdateGroup(group.id)"
            >
              <PencilIcon/>
            </IconButton>
          </div>
        </div>
      </div>
    </div>

<!--    <div class="profileFollow profileAsideBlock">-->
<!--      <div class="profileAsideBlock__heading">-->
<!--        <h4>-->
<!--          Слежу-->
<!--        </h4>-->
<!--        &lt;!&ndash;TODO: clarify design and functionality for 'все'&ndash;&gt;-->
<!--        <button class="profileAsideButton">-->
<!--          Все-->
<!--        </button>-->
<!--      </div>-->

<!--      <div class="profileFollow__wrapper">-->
<!--        <div-->
<!--          v-for="userOrGroup in user.watchingUser"-->
<!--          :key="userOrGroup.id"-->
<!--          class="profileGroup__wrapper"-->
<!--        >-->
<!--          <img-->
<!--            class="profileGeneral__img profileAsideBlock__img"-->
<!--            :src="userOrGroup.avatar || '/images/generic-user-purple.png'"-->
<!--            alt="User or Group avatar"-->
<!--          >-->
<!--          <div class="profileAsideBlock__text">-->
<!--            <p>-->
<!--              {{ userOrGroup.username }}-->
<!--            </p>-->
<!--            <span class="placeMark placeMark-small">-->
<!--              {{ userOrGroup.location.title }}-->
<!--            </span>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import IconButton from 'components/IconButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import gql from './gql';

export default {
  components: {
    IconButton,
    PencilIcon
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
      this.setEditGroupId(groupId);

      if (this.$route.fullPath !== '/profile/update-group') {
        this.$router.push('/profile/update-group');
      } else {
        this.$router.customData.navHistory.unshift(this.$route);
      }
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
        console.log('user profile update');

        const {
          avatar,
          username,
          location,
          description,
          musicGroups,
          roles
        } = myProfile;

        this.myProfile.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.myProfile.name = username;
        this.myProfile.musicGroups = musicGroups;

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
