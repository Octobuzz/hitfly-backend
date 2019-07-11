<template>
  <div class="user-card">
    <div :class="[itemContainerClass, 'user-card__item']">
      <img
        class="user-card__profile-avatar"
        :src="myProfile.avatar || anonymousAvatar"
        alt="User avatar"
      >

      <div class="user-card__profile-info">
        <p class="user-card__profile-name">
          {{ myProfile.name }}
        </p>
        <p class="user-card__followers-count">
          {{ myProfile.followersCount || '0' }} подписчиков
        </p>

        <p
          v-if="myProfile.location && myProfile.location.title"
          class="user-card__location"
        >
          {{ myProfile.location.title }}
        </p>
      </div>

      <IconButton
        v-if="!isEditingProfile"
        class="user-card__edit-button"
        @press="goToEditProfile"
      >
        <PencilIcon />
      </IconButton>
    </div>

    <div
      v-if="musicGroupCount === 0"
      :class="itemContainerClass"
    >
      <router-link
        to="/profile/create-group"
        :class="[
          'user-card__create-group-link',
          {
            hover: $route.fullPath === '/profile/create-group'
          }
        ]"
      >
        Создать свою группу
        <span class="user-card__create-group-arrow">
          <ArrowIcon />
        </span>
      </router-link>
    </div>

    <div
      v-else
      :class="[
        itemContainerClass,
        'user-card__item',
        'user-card__groups'
      ]"
    >
      <div class="user-card__item-header">
        <span>
          Мои группы
        </span>
        <button
          v-if="!isCreatingGroup"
          class="user-card__button"
          @click="goToCreateGroup"
        >
          Создать группу
        </button>
      </div>

      <div
        v-for="group in myProfile.musicGroups"
        :key="group.id"
        class="user-card__group"
      >
        <img
          class="user-card__group-cover"
          :src="
            group.avatarGroup.filter(
              avatar => avatar.size === 'size_40x40'
            )[0].url
          "
          alt="Group cover"
        >

        <div class="user-card__group-info">
          <div class="user-card__someone-info">
            <p class="user-card__group-name">
              {{ group.name }}
            </p>
            <p class="user-card__group-followers">
              {{ group.followersCount || '0' }} подписчиков
            </p>
          </div>
        </div>

        <IconButton
          v-if="!isUpdatingGroup(group.id)"
          class="user-card__edit-button"
          @press="goToUpdateGroup(group.id)"
        >
          <PencilIcon />
        </IconButton>
      </div>
    </div>

    <div
      v-if="
        myProfile.watchedUsers.length > 0
          || myProfile.watchedGroups.length > 0
      "
      :class="[
        itemContainerClass,
        'user-card__item',
        'user-card__followed'
      ]"
    >
      <div class="user-card__item-header">
        <span>
          Слежу
        </span>
        <button class="user-card__button">
          Все
        </button>
      </div>

      <div
        v-if="myProfile.watchedUsers.length > 0"
        class="user-card__followed-users"
      >
        <span class="user-card__subsection-header">
          Пользователи
        </span>

        <div
          v-for="user in myProfile.watchedUsers.slice(0, 3)"
          :key="user.id"
          class="user-card__user user-card__user-info"
        >
          <router-link :to="`/user/${user.id}/music`">
            <img
              class="user-card__user-avatar"
              :src="
                user.avatar.filter(
                  image => image.size === 'size_56x56'
                )[0].url
              "
              alt="User avatar"
            >
          </router-link>

          <div class="user-card__someone-info">
            <p class="user-card__username">
              <router-link
                class="user-card__username-link"
                :to="`/user/${user.id}/music`"
              >
                {{ user.username }}
              </router-link>
            </p>
            <p
              v-if="user.location"
              class="user-card__location user-card__location_small"
            >
              {{ user.location.title }}
            </p>
          </div>
        </div>
      </div>

      <div
        v-if="myProfile.watchedGroups.length > 0"
        class="user-card__followed-groups"
      >
        <span class="user-card__subsection-header">
          Группы
        </span>

        <div
          v-for="group in myProfile.watchedGroups.slice(0, 3)"
          :key="group.id"
          class="user-card__group user-card__group-info"
        >
          <img
            class="user-card__group-cover"
            :src="
              group.avatarGroup.filter(
                image => image.size === 'size_40x40'
              )[0].url
            "
            alt="Group cover"
          >

          <div class="user-card__someone-info">
            <p class="user-card__group-name">
              {{ group.name }}
            </p>
            <span
              v-if="group.location"
              class="user-card__location user-card__location_small"
            >
              {{ group.location.title }}
            </span>
          </div>
        </div>
      </div>
    </div>

<!--    <div :class="itemContainerClass">-->
<!--      level: {{ myProfile.bonusProgram.level }};-->
<!--      <img :src="myProfile.bonusProgram.image" alt="Level image">-->
<!--      daysPassed: {{ myProfile.bonusProgram.daysPassed }};-->
<!--      points: {{ myProfile.bonusProgram.points }};-->
<!--      progressPct: {{ myProfile.bonusProgram.progressPct }};-->
<!--      pointsToNextLevel: {{ myProfile.bonusProgram.pointsToNextLevel }}-->
<!--    </div>-->

    <div
      v-if="myProfile.activity"
      :class="[
        itemContainerClass,
        'user-card__item',
        'user-card__about'
      ]"
    >
      <div class="user-card__item-header">
        <span>
          Обо мне
        </span>
      </div>
      <p class="user-card__about-text">
        {{ myProfile.activity }}
      </p>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import IconButton from 'components/IconButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import ArrowIcon from 'components/icons/ArrowIcon.vue';
import levelNoviceImg from 'images/level-novice.svg';
import levelFanImg from 'images/level-fan.svg';
import levelConnoisseurImg from 'images/level-connoisseur.svg';
import levelMusicLoverImg from 'images/level-music-lover.svg';
import gql from './gql';

const bonusProgramLvlMap = {
  LEVEL_NOVICE: {
    title: 'Новичек',
    image: levelNoviceImg,
    nextLevelImage: levelFanImg,
    nextLevelPoints: 400
  },
  LEVEL_AMATEUR: {
    title: 'Любитель',
    image: levelFanImg,
    nextLevelImage: levelConnoisseurImg,
    nextLevelPoints: 2500
  },
  LEVEL_CONNOISSEUR_OF_THE_GENRE: {
    title: 'Знаток жанра',
    image: levelConnoisseurImg,
    nextLevelImage: levelMusicLoverImg,
    nextLevelPoints: 5000
  },
  LEVEL_SUPER_MUSIC_LOVER: {
    title: 'Супер меломан',
    image: levelMusicLoverImg,
    nextLevelImage: null,
    nextLevelPoints: null
  }
};

export default {
  components: {
    IconButton,
    PencilIcon,
    ArrowIcon
  },

  props: {
    itemContainerClass: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      myProfile: {
        avatar: '',
        name: '',
        location: null,
        followersCount: '',
        activity: '',
        musicGroups: [],
        watchedUsers: [],
        watchedGroups: [],
        bonusProgram: {
          level: '',
          points: '',
          daysPassed: '',
          progressPct: 0
        }
      },
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
      return +this.$route.params.editGroupId;
    },
    musicGroupCount() {
      return this.myProfile.musicGroups.length;
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('loading/setUserCard', {
      initialized: false
    });
    next();
  },

  methods: {
    notifyInitialization(success, key) {
      this.$store.commit('loading/setUserCard', {
        [key]: {
          initialized: true,
          success
        }
      });
    },

    isUpdatingGroup(groupId) {
      return groupId === this.editGroupId;
    },

    goToEditProfile() {
      this.$router.push('/profile/edit');
    },

    goToUpdateGroup(groupId) {
      this.$router.push(`/profile/edit-group/${groupId}`);
    },

    goToCreateGroup() {
      if (this.myProfile.musicGroups.length >= 5) {
        this.$message(
          'Создано максимальное количество групп',
          'info',
          { timeout: 2000 }
        );
        return;
      }

      this.$router.push('/profile/create-group');
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
          roles,
          dateRegister,
          bpProgressPercent: bpProgressPct,
          bpLevelBonusProgram: bpLevel,
          bpDaysInProgram: bpDaysPassed,
          bpPoints
        } = myProfile;

        // this is to prevent handling of other queries result
        if (!dateRegister) return;

        this.myProfile.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.myProfile.name = username;
        this.myProfile.musicGroups = musicGroups;

        this.myProfile.bonusProgram = {
          level: bonusProgramLvlMap[bpLevel].title,
          image: bonusProgramLvlMap[bpLevel].image,
          nextLevelImage: '',
          points: bpPoints,
          pointsToNextLevel: bonusProgramLvlMap[bpLevel].nextLevelPoints
            - bpPoints,
          daysPassed: bpDaysPassed,
          progressPct: bpProgressPct
        };

        if (location && location.title) {
          this.myProfile.location = location;
        }

        if (roles.some(role => role.slug === 'performer')) {
          if (description) {
            this.myProfile.activity = description;
          }
        }

        this.notifyInitialization(true, 'personalInfo');
      },
      error(err) {
        this.notifyInitialization(false, 'personalInfo');

        console.dir(err);
      }
    },

    watchedUsers: {
      query: gql.query.WATCHED_USERS,
      update({ watchingUser: { data } }) {
        this.myProfile.watchedUsers = data
          .map(watchedUser => watchedUser.user);

        this.notifyInitialization(true, 'watchedUsers');
      },
      error(err) {
        this.notifyInitialization(false, 'watchedUsers');

        console.dir(err);
      }
    },

    watchedGroups: {
      query: gql.query.WATCHED_GROUPS,
      update({ watchingMusicGroup: { data } }) {
        this.myProfile.watchedGroups = data
          .map(watchedGroup => watchedGroup.group);

        this.notifyInitialization(true, 'watchedGroups');
      },
      error(err) {
        this.notifyInitialization(false, 'watchedGroups');

        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyUserCard.scss"
/>
