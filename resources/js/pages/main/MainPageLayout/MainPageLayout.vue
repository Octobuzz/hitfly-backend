<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">

      <ListenedNow>

      </ListenedNow>

      <TopFifty>

      </TopFifty>
    </template>

    <template #right-column="{ itemContainerClass }">
      
    </template>
  </AppColumns>
</template>

<script>
import TopFifty from './TopFifty';
import ListenedNow from './ListenedNow';
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';

export default {
  components: {
    AppColumns,
    PageHeader,
    TopFifty,
    ListenedNow
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
