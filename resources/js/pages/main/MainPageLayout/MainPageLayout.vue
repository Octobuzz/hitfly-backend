<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">

      <ListenedNow>

      </ListenedNow>

      <TopFifty>

      </TopFifty>
    </template>

    <template #right-column="{ itemContainerClass }">
      <RecommendedTracks>

      </RecommendedTracks>

      <NewTracks>

      </NewTracks>

      <SuperMelomaniac>

      </SuperMelomaniac>

      <WeeklyTop>

      </WeeklyTop>
    </template>
  </AppColumns>
</template>

<script>
import TopFifty from './TopFifty';
import ListenedNow from './ListenedNow';
import RecommendedTracks from './RecommendedTracks/RecommendedTracks.vue';
import SuperMelomaniac from './SuperMelomaniac/SuperMelomaniac.vue';
import NewTracks from './NewTracks/NewTracks.vue';
import WeeklyTop from './WeeklyTop/WeeklyTop.vue';
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';

export default {
  components: {
    AppColumns,
    PageHeader,
    TopFifty,
    ListenedNow,
    RecommendedTracks,
    SuperMelomaniac,
    NewTracks,
    WeeklyTop,
  },
  data: () => ({

  }),
}
</script>

<style
  scoped
  lang="scss"
  src="./MainPageLayout.scss"
/>
