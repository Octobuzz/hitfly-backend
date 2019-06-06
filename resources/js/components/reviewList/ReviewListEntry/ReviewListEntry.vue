<template>
  <div>
    {{ trackId }}
  </div>
</template>

<script>
export default {
  props: {
    trackId: {
      type: Number,
      required: true
    },
  }
};
</script>
