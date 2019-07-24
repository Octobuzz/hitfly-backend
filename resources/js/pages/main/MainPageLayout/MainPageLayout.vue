<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">
      <div
        v-show="topFiftyIsLoading || listeningNowIsLoading"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>
      <TopFifty>

      </TopFifty>
    </template>

    <template
      v-if="desktop || !showFirstLoader"
      #right-column="{ paddingClass }"
    >
    </template>
  </AppColumns>
</template>

<script>
import * as TopFifty from './TopFifty';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';

export default {
  components: {
    SpinnerLoader,
    AppColumns,
    PageHeader,
    TopFifty
  },
  data: () => ({
    topFiftyIsLoading: true,
    listeningNowIsLoading: true,
  }),
}
</script>

<style
  scoped
  lang="scss"
  src="./MainPageLayout.scss"
/>
