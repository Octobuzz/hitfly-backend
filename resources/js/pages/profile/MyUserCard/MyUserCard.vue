<template>
  <div class="user-card">
    <div :class="[itemContainerClass, 'user-card__item']">
      <div class="user-card__profile-avatar">
        <img
          class="user-card__profile-avatar-img"
          :src="myProfile.avatar || anonymousAvatar"
          alt="User avatar"
        >
      </div>

      <div class="user-card__profile-info">
        <p class="user-card__profile-name">
          <WordTrimmedWithTooltip
            :word="myProfile.name"
          />
        </p>
        <p class="user-card__followers-count">
          {{ myProfile.followersCount || '0' }}
          {{ format('FOLLOWER', myProfile.followersCount) }}
        </p>

        <p
          v-if="myProfile.location && myProfile.location.title"
          class="user-card__location"
        >
          {{ myProfile.location.title }}
        </p>

        <p
          v-if="playedGenres && !hasRole('prof_critic') && !hasRole('star')"
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

    <template v-if="!hasRole('prof_critic') && !hasRole('star')">
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
            v-if="ableToPerform && !isCreatingGroup"
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
          <div class="user-card__group-cover">
            <img
              class="user-card__group-cover-img"
              :src="
                group.avatarGroup.filter(
                  avatar => avatar.size === 'size_40x40'
                )[0].url
              "
              alt="Group cover"
            >
          </div>

          <div class="user-card__group-info">
            <div class="user-card__someone-info">
              <p class="user-card__group-name">
                <WordTrimmedWithTooltip
                  :word="group.name"
                />
              </p>
              <p class="user-card__group-followers">
                {{ group.followersCount || '0' }}
                {{ format('FOLLOWER', group.followersCount) }}
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
    </template>

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
        <router-link
          to="/profile/watched-users"
          class="user-card__button"
        >
          Все
        </router-link>
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
            <div class="user-card__user-avatar">
              <img
                class="user-card__user-avatar-img"
                :src="
                  user.avatar.filter(
                    image => image.size === 'size_56x56'
                  )[0].url
                "
                alt="User avatar"
              >
            </div>
          </router-link>

          <div class="user-card__someone-info">
            <p class="user-card__username">
              <WordTrimmedWithTooltip
                :class="[
                  'user-card__username-link',
                  'user-card__watched-user-link'
                ]"
                :word="user.username"
                @click="goToUserProfile(user)"
              />
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
          <div class="user-card__group-cover">
            <img
              class="user-card__group-cover-img"
              :src="
                group.avatarGroup.filter(
                  image => image.size === 'size_40x40'
                )[0].url
              "
              alt="Group cover"
            >
          </div>

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

    <div
      v-if="!hasRole('prof_critic') && !hasRole('star')"
      :class="itemContainerClass"
    >
      <p class="user-card__bonus-program-p user-card__bonus-program-status">
        <img
          :src="myProfile.bonusProgram.image"
          alt="Bonus program level"
          class="user-card__bonus-program-image"
        >
        <span class="h3 user-card__bonus-program-h">
          {{ myProfile.bonusProgram.level }}
        </span>

        <router-link
          to="/profile/bonus-program"
          class="user-card__button user-card__bonus-program-button"
        >
          Подробнее
        </router-link>
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

      <p
        v-else-if="myProfile.bonusProgram.tracksToNextLevel"
        class="user-card__bonus-program-p"
      >
        <span>
          Чтобы стать
          <img
            :src="myProfile.bonusProgram.nextLevelImage"
            alt="Bonus program level"
            class="user-card__bonus-program-image"
          >
          {{ myProfile.bonusProgram.nextStatusListenedTracksDesc }}
        </span>
      </p>

      <div class="user-card__bonus-program">
        <span class="user-card__bonus-program-followers">
          <BpFollowers />
          <span>
            {{ myProfile.bonusProgram.points || '0' }}
            {{ format('BONUS', myProfile.bonusProgram.points || '0') }}
          </span>
        </span>

        <span class="user-card__bonus-program-days">
          <BpDaysPassed />
          <span>
            {{ myProfile.bonusProgram.daysPassed || '0' }}
            {{ format('DAY', myProfile.bonusProgram.daysPassed || '0') }}
          </span>
        </span>

        <span class="user-card__bonus-program-favourites">
          <BpFavouriteTracks />
          {{ myProfile.favouriteTracksCount }}
          {{ format('FAVOURITE_SONG', myProfile.favouriteTracksCount) }}
        </span>
      </div>

      <div
        v-if="myProfile.bonusProgram.listenedTracksByGenre.length > 0"
        class="user-card__bonus-program-genres"
      >
        <p class="h5 user-card__bonus-program-genres-header">
          Прослушанные треки по жанрам
        </p>
        <div
          v-for="genre in myProfile.bonusProgram.listenedTracksByGenre"
          :key="genre.id"
          class="user-card__bonus-program-genres-item"
        >
          <img :src="genre.image" alt="Жанр">
          <div>
            <p class="h5 user-card__bonus-program-genres-title">
              {{ genre.name }}
            </p>
            <span class="user-card__bonus-program-genres-tracks">
              {{ genre.countListenedByUser }}
              {{ format('TRACK', genre.countListenedByUser) }}
            </span>
          </div>
        </div>
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
import { mapGetters } from 'vuex';
import { bonusProgramLvl } from 'modules/bonus-program';
import endingFormatter from 'modules/plural-form-endings-formatter';
import anonymousAvatar from 'images/anonymous-avatar.png';
import WordTrimmedWithTooltip from 'components/WordTrimmedWithTooltip';
import IconButton from 'components/IconButton.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import ArrowIcon from 'components/icons/ArrowIcon.vue';
import NoteIcon from 'components/icons/NoteIconGrey.vue';
import BpFollowers from 'components/icons/BpFollowers.vue';
import BpDaysPassed from 'components/icons/BpDaysPassed.vue';
import BpFavouriteTracks from 'components/icons/BpFavouriteTracks.vue';
import gql from './gql';

export default {
  components: {
    WordTrimmedWithTooltip,
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
        favouriteTracksCount: '0',
        activity: '',
        playedGenres: [],
        musicGroups: [],
        watchedUsers: [],
        watchedGroups: [],
        bonusProgram: {
          level: '',
          points: '',
          daysPassed: '',
          listenedTracksByGenre: []
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

    showAboutMe() {
      return this.hasRole('performer')
        || this.hasRole('critic')
        || this.hasRole('prof_critic')
        || this.hasRole('star');
    },

    playedGenres() {
      const { playedGenres } = this.myProfile;

      if (!playedGenres || playedGenres.length === 0) {
        return null;
      }

      return playedGenres.map(genre => (
        genre.name[0].toUpperCase() + genre.name.slice(1)
      )).join(', ');
    },

    ...mapGetters({
      ableToPerform: 'profile/ableToPerform',
      hasRole: 'profile/roles'
    })
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

    goToUserProfile(user) {
      const isProfCriticOrStar = user.roles.some(
        role => role.slug === 'prof_critic' || role.slug === 'star'
      );

      if (isProfCriticOrStar) {
        this.$router.push(`/user/${user.id}/user-reviews`);

        return;
      }

      this.$router.push(`/user/${user.id}/music`);
    },

    format(word, count) {
      return endingFormatter(word, count);
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
          followersCount,
          favoriteSongsCount: favouriteSongsCount,
          dateRegister,
          bpLevelBonusProgram: bpLevelSlug,
          bpDaysInProgram: bpDaysPassed,
          bpPoints,
          bpListenedTracksByGenres,
          countListenedTracks: bpListenedTracks
        } = myProfile;

        // this is to prevent handling of other queries result
        if (!dateRegister) return;

        this.myProfile.avatar = avatar
          .filter(image => image.size === 'size_56x56')[0].url;

        this.myProfile.name = username;
        this.myProfile.playedGenres = genresPlay;
        this.myProfile.musicGroups = musicGroups;
        this.myProfile.favouriteTracksCount = favouriteSongsCount;
        this.myProfile.followersCount = followersCount;

        const bpLevel = bonusProgramLvl[bpLevelSlug];
        const bpNextLevel = bonusProgramLvl[bpLevel.nextLevelSlug];

        this.myProfile.bonusProgram = {
          level: bpLevel.title,
          image: bpLevel.image,
          nextLevelImage: bpNextLevel && bpNextLevel.image,
          nextLevelText: bpNextLevel && bpNextLevel.title,
          points: bpPoints,
          nextStatusListenedTracksDesc: bpNextLevel && bpNextLevel.levelListenedTracksDesc,
          pointsToNextLevel: bpNextLevel
            ? Math.max(0, bpNextLevel.points - bpPoints)
            : 0,
          tracksToNextLevel: bpNextLevel
            ? Math.max(0, bpNextLevel.listenedTracks - bpListenedTracks)
            : 0,
          daysPassed: bpDaysPassed,
          listenedTracksByGenre: bpListenedTracksByGenres
        };

        if (location && location.title) {
          this.myProfile.location = location;
        }

        if (this.hasRole('performer')
            || this.hasRole('critic')
            || this.hasRole('prof_critic')
            || this.hasRole('star')) {
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
