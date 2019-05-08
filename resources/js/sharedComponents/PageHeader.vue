<template>
  <span :class="['page-header', $attrs.class]">
    <slot/>
  </span>
</template>

<style
  scoped
  lang="scss"
>
.page-header {
  font-family: 'Gotham Pro', serif;
  font-size: 12px;
  color: #b4b4b4;
  display: block;

  @media screen and (max-width: 767px) {
    font-size: 10px;
  }
}
</style>
