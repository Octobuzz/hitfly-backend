<template>
  <div class="faq-page-wrapper">
    <span class="h2">
      Раздел в разработке
    </span>
    <span>
      Если у вас есть вопросы, направляйте их к нам на почту info@myhitfly.ru.
    </span>
  </div>
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
  }
};
</script>

<style
  scoped
  lang="scss"
>
.faq-page-wrapper {
  padding: 32px;
}
</style>
