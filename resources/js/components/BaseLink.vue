<template>
  <a :class="['base-link', $attrs.class]">
    <slot />
  </a>
</template>

<style
  scoped
  lang="scss"
>
.base-link {
  user-select: none;
  cursor: pointer;

  &:hover {
    color: #8181e2;;
  }
}
</style>
