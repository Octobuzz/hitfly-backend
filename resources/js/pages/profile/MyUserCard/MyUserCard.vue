<template>
<!--  <div-->
<!--    :class="[-->
<!--      'user-card',-->
<!--      { 'user-card_loading': !dataInitialized }-->
<!--    ]"-->
<!--  >-->
<!--    <SpinnerLoader-->
<!--      v-if="!dataInitialized"-->
<!--      class="user-card__loader"-->
<!--    />-->
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
            )[0].url || anonymousAvatar
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
        myProfile.watchingUser.length > 0
          || myProfile.watchingMusicGroup.length > 0
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
        v-if="myProfile.watchingUser.length > 0"
        class="user-card__followed-users"
      >
        <span class="user-card__subsection-header">
          Пользователи
        </span>

        <div
          v-for="user in myProfile.watchingUser.slice(0, 3)"
          :key="user.id"
          class="user-card__user user-card__user-info"
        >
          <img
            class="user-card__user-avatar"
            :src="
              user.avatar.filter(
                image => image.size === 'size_56x56'
              )[0].url || anonymousAvatar"
            alt="User avatar"
          >

          <div class="user-card__someone-info">
            <p class="user-card__username">
              {{ user.username }}
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
        v-if="myProfile.watchingMusicGroup.length > 0"
        class="user-card__followed-groups"
      >
        <span class="user-card__subsection-header">
          Группы
        </span>

        <div
          v-for="group in myProfile.watchingMusicGroup.slice(0, 3)"
          :key="group.id"
          class="user-card__group user-card__group-info"
        >
          <img
            class="user-card__group-cover"
            :src="
              group.avatarGroup.filter(
                image => image.size === 'size_40x40'
              )[0].url || anonymousAvatar"
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

<!--      <div :class="itemContainerClass">-->
<!--        level: {{ myProfile.bonusProgram.level }};-->
<!--        daysPassed: {{ myProfile.bonusProgram.daysPassed }};-->
<!--        points: {{ myProfile.bonusProgram.points }};-->
<!--        progressPct: {{ myProfile.bonusProgram.progressPct }};-->
<!--      </div>-->

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
import SpinnerLoader from 'components/SpinnerLoader.vue';
import IconButton from 'components/IconButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import ArrowIcon from 'components/icons/ArrowIcon.vue';
import gql from './gql';

const bonusProgramLvlMap = {
  LEVEL_NOVICE: 'Новичек',
  LEVEL_AMATEUR: 'Любитель',
  LEVEL_CONNOISSEUR_OF_THE_GENRE: 'Знаток жанра',
  LEVEL_SUPER_MUSIC_LOVER: 'Супер меломан'
};

export default {
  components: {
    SpinnerLoader,
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
        watchingUser: [],
        watchingMusicGroup: [],
        bonusProgram: {
          level: '',
          points: '',
          daysPassed: '',
          progressPct: 0
        }
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
      return +this.$route.params.editGroupId;
    },
    musicGroupCount() {
      return this.myProfile.musicGroups.length;
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('loading/setMyUserCard', {
      initialized: false
    });
    next();
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMyUserCard', {
        initialized: true,
        success
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
          watchingUser,
          watchingMusicGroup,
          roles,
          bpProgressPercent: bpProgressPct,
          bpLevelBonusProgram: bpLevel,
          bpDaysInProgram: bpDaysPassed,
          bpPoints
        } = myProfile;

        this.myProfile.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.myProfile.name = username;
        this.myProfile.musicGroups = musicGroups;
        this.myProfile.watchingUser = watchingUser;
        this.myProfile.watchingMusicGroup = watchingMusicGroup;

        // TODO: uncomment when the api is fixed

        // this.myProfile.bonusProgram = {
        //   level: bonusProgramLvlMap[bpLevel],
        //   daysPassed: bpDaysPassed,
        //   points: bpPoints,
        //   progressPct: bpProgressPct
        // };

        if (location && location.title) {
          this.myProfile.location = location;
        }

        if (roles.some(role => role === 'Артист')) {
          if (description) {
            this.myProfile.activity = description;
          }
        }

        this.notifyInitialization(true);
      },
      error(err) {
        this.notifyInitialization(false);

        console.dir(err);
      }
    },
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyUserCard.scss"
/>
