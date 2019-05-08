<template>
  <div
    :class="[
      'lds-spinner',
      { 'lds-spinner_dark': theme === 'light' },
      $attrs.class
    ]"
  >
    <div/>
    <div/>
    <div/>
    <div/>
    <div/>
    <div/>
    <div/>
    <div/>
  </div>
</template>

<script>
export default {
  props: {
    theme: {
      type: String,
      default: 'light'
    }
  }
}
</script>

<style
  scoped
  lang="scss"
>
.lds-spinner {
  display: inline-block;
  position: relative;
  width: 64px;
  height: 64px;
}
.lds-spinner div {
  transform-origin: 32px 32px;
  animation: lds-spinner .8s linear infinite;
}
.lds-spinner div:after {
  content: " ";
  display: block;
  position: absolute;
  top: 3px;
  left: 29px;
  width: 5px;
  height: 18px;
  border-radius: 2px;
  background: white;
}
.lds-spinner_dark div:after {
  background: #999;
}

@for $i from 1 through 8 {
  .lds-spinner div:nth-child(#{$i}) {
    transform: rotate($i * 360deg / 8);
    animation-delay: -0.1s * $i;
  }
}

@keyframes lds-spinner {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
</style>
