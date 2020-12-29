<template>
  <AppColumns>
    <template #left-column>
      <div class="life-hacks__sidebar-wrapper">
        <LifeHacksCategoryList />
      </div>
    </template>

    <template #right-column>
      <div :class="[containerPaddingClass]">
        <PageHeader class="life-hacks__content-section-name">
          ЛАЙФХАКИ
        </PageHeader>

        <span class="h2 life-hacks__content-section-title">
          Лайфхаки
        </span>

        <LifeHacksItemTable />
      </div>
    </template>
  </AppColumns>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';
import LifeHacksCategoryList from '../LifeHacksCategoryList';
import LifeHacksItemTable from '../LifeHacksItemTable';

export default {
  components: {
    AppColumns,
    PageHeader,
    LifeHacksCategoryList,
    LifeHacksItemTable
  },

  mixins: [containerPaddingClass]
};
</script>

<style
  lang="scss"
  scoped
  src="./LifeHacks.scss"
/>
