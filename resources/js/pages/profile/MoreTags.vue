<template>
  <span
    class="more-tags"
    @click="$emit('press')"
  >
    Ещё
  </span>
</template>

<style
  scoped
  lang="scss"
>
@import '../../../sass/variables';

$borderRadius: 20px;

.more-tags {
  font-family: "Gotham Pro", serif;
  font-size: 12px;
  line-height: 16px;
  color: $color_3;
  padding: 12px 18px 12px 18px;;
  position: relative;
  margin-bottom: 11px;
  margin-top: auto;
  transform: translateY(1px);
  border-radius: $borderRadius;
  border: 1px solid $color_purple;
  cursor: pointer;
  user-select: none;
  transition: background-color .2s;

  &:hover {
    color: white;
    background: $linear-gradient;
    padding: 13px 19px 13px 19px;
    border: none;
  }
}
</style>
