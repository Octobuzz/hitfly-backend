<template>
  <svg
    style="width:0;height:0;position:absolute;"
    aria-hidden="true"
    focusable="false"
  >
    <radialGradient
      id="icon-gradient-radial"
      gradientUnits="userSpaceOnUse"
      cx="5"
      cy="5"
      r="25"
    >
      <stop offset="0%" stop-color="black" />
      <stop offset="70%" stop-color="black" />
    </radialGradient>
  </svg>
</template>

<style
  scoped
  lang="scss"
>
@import '~sass/variables';

stop {
  &:first-child {
    stop-color: $gradient_color_start;
  }
  &:last-child {
    stop-color: $gradient_color_end;
  }
}
</style>
