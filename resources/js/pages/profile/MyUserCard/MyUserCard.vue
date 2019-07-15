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

        <p
          v-if="playedGenres"
          class="user-card__played-genres-container"
        >
          <span class="user-card__played-genres-icon">
            <NoteIcon />
          </span>
          <span class="user-card__played-genres">
            {{ playedGenres }}
          </span>
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

    <div :class="itemContainerClass">
      <p class="user-card__bonus-program-p">
        <img
          :src="myProfile.bonusProgram.image"
          alt="Bonus program level"
          class="user-card__bonus-program-image"
        >
        <span class="h3 user-card__bonus-program-h">
          {{ myProfile.bonusProgram.level }}
        </span>
      </p>

      <p
        v-if="myProfile.bonusProgram.pointsToNextLevel"
        class="user-card__bonus-program-p"
      >
        <span>
          до
          <img
            :src="myProfile.bonusProgram.nextLevelImage"
            alt="Bonus program level"
            class="user-card__bonus-program-image"
          >
          {{ myProfile.bonusProgram.nextLevelText }}
          осталось
          <span class="h5 user-card__bonus-program-h">
            {{ myProfile.bonusProgram.pointsToNextLevel }} б
          </span>
        </span>
      </p>

      <div class="user-card__bonus-program">
        <span class="user-card__bonus-program-followers">
          <BpFollowers />
          <span>
            {{ myProfile.bonusProgram.points || '0' }}
            {{ format('bonuses', myProfile.bonusProgram.points || '0') }}
          </span>
        </span>

        <span class="user-card__bonus-program-days">
          <BpDaysPassed />
          <span>
            {{ myProfile.bonusProgram.daysPassed || '0' }}
            {{ format('days', myProfile.bonusProgram.daysPassed || '0') }}
          </span>
        </span>
        <span class="user-card__bonus-program-favourites">
          <BpFavouriteTracks />
  <!--        {{ user.favouritesTrackCount }}-->
  <!--        {{ format('favourites', user.favouritesTrackCount) }}-->
        </span>
      </div>
    </div>

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
import NoteIcon from 'components/icons/NoteIconGrey.vue';
import BpFollowers from 'components/icons/BpFollowers.vue';
import BpDaysPassed from 'components/icons/BpDaysPassed.vue';
import BpFavouriteTracks from 'components/icons/BpFavouriteTracks.vue';
import levelNoviceImg from 'images/level-novice.svg';
import levelFanImg from 'images/level-fan.svg';
import levelConnoisseurImg from 'images/level-connoisseur.svg';
import levelMusicLoverImg from 'images/level-music-lover.svg';
import gql from './gql';

const formatting = {
  bonuses: {
    1: 'поклонник',
    234: 'поклонника',
    567890: 'поклонников'
  },
  days: {
    1: 'день в Hitfly',
    234: 'дня в Hitfly',
    567890: 'дней Hitfly'
  },
  favourites: {
    1: 'любимая песня',
    234: 'любимые песни',
    567890: 'любимых песен'
  }
};

const bonusProgramLvlMap = {
  LEVEL_NOVICE: {
    title: 'Новичек',
    image: levelNoviceImg,
    nextLevelImage: levelFanImg,
    nextLevelText: 'Любителя',
    nextLevelPoints: 400,
    slug: 'LEVEL_NOVICE'
  },
  LEVEL_AMATEUR: {
    title: 'Любитель',
    image: levelFanImg,
    nextLevelImage: levelConnoisseurImg,
    nextLevelText: 'Знатока жанра',
    nextLevelPoints: 2500,
    slug: 'LEVEL_AMATEUR'
  },
  LEVEL_CONNOISSEUR_OF_THE_GENRE: {
    title: 'Знаток жанра',
    image: levelConnoisseurImg,
    nextLevelImage: levelMusicLoverImg,
    nextLevelText: 'Супер меломана',
    nextLevelPoints: 5000,
    slug: 'LEVEL_CONNOISSEUR_OF_THE_GENRE'
  },
  LEVEL_SUPER_MUSIC_LOVER: {
    title: 'Супер меломан',
    image: levelMusicLoverImg,
    nextLevelImage: null,
    nextLevelPoints: null,
    slug: 'LEVEL_SUPER_MUSIC_LOVER'
  }
};

export default {
  components: {
    BpFollowers,
    BpDaysPassed,
    BpFavouriteTracks,
    IconButton,
    PencilIcon,
    ArrowIcon,
    NoteIcon
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
        playedGenres: [],
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
    },

    playedGenres() {
      const { playedGenres } = this.myProfile;

      if (!playedGenres || playedGenres.length === 0) {
        return null;
      }

      return playedGenres.map(genre => (
        genre.name[0].toUpperCase() + genre.name.slice(1)
      )).join(', ').slice(0, -2);
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
    },

    format(type, count) {
      const lastDigit = +count % 10;
      let lastDigitSet = 567890;

      if (lastDigit === 1) {
        lastDigitSet = 1;
      }
      if (lastDigit >= 2 && lastDigit <= 4) {
        lastDigitSet = 234;
      }

      return formatting[type][lastDigitSet];
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
          genresPlay,
          musicGroups,
          roles,
          dateRegister,
          bpLevelBonusProgram: bpLevel,
          bpDaysInProgram: bpDaysPassed,
          bpPoints
        } = myProfile;

        // this is to prevent handling of other queries result
        if (!dateRegister) return;

        this.myProfile.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.myProfile.name = username;
        this.myProfile.playedGenres = genresPlay;
        this.myProfile.musicGroups = musicGroups;

        this.myProfile.bonusProgram = {
          level: bonusProgramLvlMap[bpLevel].title,
          image: bonusProgramLvlMap[bpLevel].image,
          nextLevelImage: bonusProgramLvlMap[bpLevel].nextLevelImage,
          nextLevelText: bonusProgramLvlMap[bpLevel].nextLevelText,
          points: bpPoints,
          pointsToNextLevel: bonusProgramLvlMap[bpLevel].nextLevelPoints
            - bpPoints,
          daysPassed: bpDaysPassed
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
