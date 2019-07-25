<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">
      <div
        v-show="showLoader"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>

      <MyUserCard
        v-show="!showLoader"
        :item-container-class="itemContainerClass"
      />
    </template>

    <template
      v-if="!showLoader"
      #right-column="{ paddingClass }"
    >
      <span :class="['h2', paddingClass]">
        Раздел в разработке
      </span>
      <span :class="paddingClass">
        Если у вас есть вопросы, направляйте их к нам на почту.
      </span>
    </template>
  </AppColumns>
</template>

<script>
import MyUserCard from 'pages/profile/MyUserCard';
import AppColumns from 'components/layout/AppColumns.vue';
import SpinnerLoader from 'components/SpinnerLoader.vue';

export default {
  components: {
    AppColumns,
    SpinnerLoader,
    MyUserCard
  },

  computed: {
    showLoader() {
      const {
        personalInfo,
        watchedUsers,
        watchedGroups
      } = this.$store.getters['loading/userCard'];

      return !(
        personalInfo.initialized
        && watchedUsers.initialized
        && watchedGroups.initialized
      );
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./profile/MyProfileLayout/MyProfileLayout.scss"
>
