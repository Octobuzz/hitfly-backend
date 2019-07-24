<template>
  <div
    v-if="bonusProgram"
    class="header-profile-popover-bonus-program"
  >
    <span class="header-profile-popover-bonus-program__status-section">
      <img
        :src="bonusProgram.currentImg"
        alt="Статус"
        class="header-profile-popover-bonus-program__image"
      >
      {{ bonusProgram.currentStatus }}
    </span>

    <span class="header-profile-popover-bonus-program__arrow-section">
      <HeaderProfilePopoverArrow />
    </span>

    <span
      v-if="bonusProgram && bonusProgram.nextLevel"
      class="header-profile-popover-bonus-program__next-level-section"
    >
      до
      <img
        :src="bonusProgram.nextImg"
        alt="Статус"
        class="header-profile-popover-bonus-program__image"
      >
      {{ formatNextStatus(bonusProgram.nextStatus) }}
      осталось
      <span class="header-profile-popover-bonus-program__points">
        {{ bonusProgram.pointsToNextLevel }} б
      </span>
    </span>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { bonusProgramLvl } from 'modules/bonus-program';
import pluralFormEndingsFormatter from 'mixins/pluralFormEndingsFormatter';
import HeaderProfilePopoverArrow from 'components/icons/HeaderProfilePopoverArrow.vue';
import gql from './gql';

export default {
  components: {
    HeaderProfilePopoverArrow
  },

  mixins: [pluralFormEndingsFormatter],

  data() {
    return {
      bonusProgram: null
    };
  },

  computed: {
    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  methods: {
    formatNextStatus(nextStatus) {
      // eslint-disable-next-line consistent-return
      const getWordFromStatus = (status) => {
        // eslint-disable-next-line default-case
        switch (status) {
          case 'LEVEL_NOVICE':
            return 'NOVICE';

          case 'LEVEL_AMATEUR':
            return 'AMATEUR';

          case 'LEVEL_CONNOISSEUR_OF_THE_GENRE':
            return 'GENRE_CONNOISSEUR';

          case 'LEVEL_SUPER_MUSIC_LOVER':
            return 'MUSIC_LOVER';
        }
      };

      const formattedWord = getWordFromStatus(nextStatus);
      const result = this.pluralFormEndingsFormatter(formattedWord, 2);

      return result.split(' ').map(word => (
        word.charAt(0).toUpperCase() + word.slice(1)
      )).join(' ');
    }
  },

  apollo: {
    bonusProgram: {
      $client() {
        return this.apolloClient;
      },
      query: gql.query.MY_PROFILE_BONUS_PROGRAM,
      update({ myProfile }) {
        const {
          bpLevelBonusProgram: levelSlug,
          bpPoints: points
        } = myProfile;

        const level = bonusProgramLvl[levelSlug];
        const nextLevel = level.nextLevelSlug
          ? bonusProgramLvl[level.nextLevelSlug]
          : null;

        const currentImg = level.image;
        const currentStatus = level.title;

        let nextImg = null;
        let nextStatus = null;
        let pointsToNextLevel = null;

        if (nextLevel) {
          nextImg = nextLevel.image;
          nextStatus = nextLevel.slug;
          pointsToNextLevel = Math.max(0, nextLevel.points - points);
        }

        return {
          currentImg,
          currentStatus,
          nextLevel,
          nextImg,
          nextStatus,
          pointsToNextLevel
        };
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./HeaderProfilePopoverBonusProgram.scss"
/>
