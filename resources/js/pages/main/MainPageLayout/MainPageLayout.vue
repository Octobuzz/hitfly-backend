<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">

      <ListenedNow>

      </ListenedNow>

      <TopFifty>

      </TopFifty>

      <SidebarStars>

      </SidebarStars>

    </template>

    <template #right-column="{ itemContainerClass }">
      <router-view></router-view>
    </template>
  </AppColumns>
</template>

<script>
import TopFifty from './TopFifty';
import ListenedNow from './ListenedNow';
import SidebarStars from './SidebarStars';
import AppColumns from 'components/layout/AppColumns.vue';

export default {
  components: {
    AppColumns,
    TopFifty,
    ListenedNow,
    SidebarStars
  },
  data: () => ({

  }),
}
</script>
