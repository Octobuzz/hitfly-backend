<template>
  <div :class="['return-header', $attrs.class]">
    <IconButton
      class="return-header__arrow-button"
      @press="onReturn"
    >
      <ArrowIcon/>
    </IconButton>
    Назад
  </div>
</template>

<script>
import IconButton from '../../sharedComponents/IconButton.vue';
import ArrowIcon from '../../sharedComponents/icons/ArrowIcon.vue';

export default {
  components: {
    IconButton,
    ArrowIcon
  },

  methods: {
    onReturn() {
      this.$emit('press');

      const { $store, $router } = this;
      const { navHistory } = $router.customData;
      const toEditGroup = navHistory[1].fullPath === '/profile/update-group';
      const fromEditGroup = $store.getters['profile/editGroupId'] !== null;

      if (fromEditGroup) {
        $store.commit('profile/popEditGroupId');
      }

      if (fromEditGroup && toEditGroup) {
        const editGroupIdHistory = $store.getters['profile/editGroupIdHistory'];
        const nextEditGroupIdIdx = editGroupIdHistory.length - 2;

        this.$router.customData.navHistory.shift();

        const nextEditGroupId = editGroupIdHistory[
          Math.max(nextEditGroupIdIdx, 0)
        ];

        $store.commit('profile/setEditGroupId', {
          id: nextEditGroupId,
          dontAffectHistory: true
        });

        return;
      }

      if (!fromEditGroup && toEditGroup) {
        const editGroupIdHistory = $store.getters['profile/editGroupIdHistory'];
        const nextEditGroupIdIdx = editGroupIdHistory.length - 1;
        const nextEditGroupId = editGroupIdHistory[
          Math.max(nextEditGroupIdIdx, 0)
        ];

        $store.commit('profile/setEditGroupId', {
          id: nextEditGroupId,
          dontAffectHistory: true
        });
      }

      navHistory.splice(0, 2);
      $router.go(-1);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../../sass/variables';

.return-header {
  font-family: "Gotham Pro", serif;
  font-size: 14px;
  display: flex;
  align-items: center;
  height: 32px;
  padding: {
    top: 16px;
    bottom: 16px;
  }
  box-shadow: 0 1px 0 $borderColor;

  &__arrow-button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 32px;
    margin-right: 16px;
    border: 1px solid $borderColor;
    border-radius: 4px;

    svg {
      width: 23px;
      height: 15px;
      opacity: .75;

      &:hover {
        opacity: 1;
      }
    }
  }
}
</style>
