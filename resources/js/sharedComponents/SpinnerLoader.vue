<template>
  <div
    :class="[
      'lds-spinner',
      {
        'lds-spinner_dark': theme === 'light',
        'lds-spinner_small': size === 'small'
      },
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
    },
    size: {
      type: String,
      default: 'normal'
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

$normal-size: 64px;
$small-size: 32px;

.lds-spinner {
  display: inline-block;
  position: relative;
  width: $normal-size;
  height: $normal-size;
  box-shadow: none;

  div {
    transform-origin: $normal-size / 2 $normal-size / 2;
    animation: lds-spinner .8s linear infinite;
  }

  div:after {
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
}

.lds-spinner_small {
  width: $small-size;
  height: $small-size;

  div {
    transform-origin: $small-size / 2 $small-size / 2;
  }

  div:after {
    top: 1px;
    left: 14px;
    width: 3px;
    height: 9px;
  }
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
