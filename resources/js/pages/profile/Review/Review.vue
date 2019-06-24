<template>
  <div>
    review on track {{ reviewId }}
  </div>
</template>

<script>
export default {
  props: {
    reviewId: {
      type: Number,
      required: true
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./Review.scss"
/>
