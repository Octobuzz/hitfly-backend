<template>
  <section class="detailedNews">

  </section>
</template>
<script>
  export default {
    data: () => ({
      objData: {
        content: 'blabla'
      }
    }),
    computed: {
      newsId() {
        const { newsId } = this.$route.params;
        console.log(this.$route.params);
        // return +(playlistId || setId);
      },
    }
  }
</script>
