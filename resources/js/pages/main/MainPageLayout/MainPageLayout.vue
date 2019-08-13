<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">

      <ListenedNow>

      </ListenedNow>

      <TopFifty>

      </TopFifty>

    </template>

    <template #right-column="{ itemContainerClass }">
      <router-view></router-view>
    </template>
  </AppColumns>
</template>

<script>
import TopFifty from './TopFifty';
import ListenedNow from './ListenedNow';
import AppColumns from 'components/layout/AppColumns.vue';

export default {
  components: {
    AppColumns,
    TopFifty,
    ListenedNow,
  },
  data: () => ({

  }),
}
</script>
