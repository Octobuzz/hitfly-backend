<template>
  <button
    :class="[
      'icon-button',
      `icon-button_${passive}`,
      `icon-button_${hover || passive}_hover`,
      {
        'icon-button_active': active
      },
      $attrs.class
    ]"
    @click="$emit('press')"
  >
    <slot/>
  </button>
</template>

<script>
// Names of possible values corresponds to those in design layout.

const possibleValues = [
  'standard-passive',
  'standard-hover',
  'secondary-passive',
  'secondary-hover'
];

const validator = val => possibleValues.some(pv => pv === val);

export const props = {
  passive: {
    type: String,
    default: 'standard-passive',
    validator
  },
  hover: {
    type: String,
    default: 'standard-hover',
    validator
  },
  active: {
    type: Boolean,
    default: false
  }
};

export default {
  props
};
</script>

<style
  scoped
  lang="scss"
>
@import '~sass/variables';

.icon-button {
  position: relative;
  min-width: 40px;
  max-width: 40px;
  min-height: 40px;
  max-height: 40px;
  border-radius: 20px;
  overflow: hidden;
  transition: .2s;

  & > * {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  &_standard-passive,
  &_standard-passive_hover:hover {

  }

  &_standard-hover,
  &_standard-hover_hover:hover {
    background: #e4e4e4;
  }

  &_secondary-passive,
  &_secondary-passive_hover:hover {
    fill: #a6a6a6;
  }

  &_secondary-hover,
  &_secondary-hover_hover:hover {
    background: white;
    fill: #313131;
  }

  &_active::v-deep svg {
    fill: url(#icon-gradient-radial);
  }
}
</style>
