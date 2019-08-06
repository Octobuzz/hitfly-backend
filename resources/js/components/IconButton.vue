<template>
  <button
    v-tooltip="computedTooltip"
    :class="[
      'icon-button',
      `icon-button_${passive}`,
      `icon-button_${hover || passive}_hover`,
      {
        'icon-button_active': active,
        'icon-button_squared': modifier.split(' ').includes('squared'),
        'icon-button_bordered': modifier.split(' ').includes('bordered')
      },
      $attrs.class
    ]"
    @click="$emit('press')"
  >
    <slot />
  </button>
</template>

<script>
const MOBILE_WIDTH = 767;

// Names of possible values corresponds to those in design layout.

const possibleValues = [
  'standard-passive',
  'standard-hover',
  'secondary-passive',
  'secondary-hover',
  'mobile-passive',
  'mobile-hover'
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
  modifier: {
    type: String,
    default: '',
    validator: (val) => {
      const possibleMods = new Set(['', 'squared', 'bordered']);
      const mods = val.split(' ');

      return mods.every(mod => possibleMods.has(mod));
    }
  },
  active: {
    type: Boolean,
    default: false
  },
  tooltip: {
    type: Object,
    default: null
  }
};

export default {
  props,
  computed: {
    computedTooltip() {
      if (this.tooltip === null) {
        return { enabled: false };
      }

      return {
        enabled: this.windowWidth > MOBILE_WIDTH,
        template: `
          <div class="icon-button-tooltip" role="tooltip">
            <div class="icon-button-tooltip__arrow"></div>
            <div class="icon-button-tooltip__inner"></div>
          </div>
        `,
        classes: 'icon-button-tooltip',
        innerSelector: '.icon-button-tooltip__inner',
        arrowSelector: '.icon-button-tooltip__arrow',
        delay: {
          show: 400,
          hide: 100
        },
        placement: 'top',
        offset: 10,
        trigger: 'hover',
        autoHide: true,
        hideOnTargetClick: true,
        ...this.tooltip
      };
    }
  }
};
</script>

<style lang="scss">
@import '~scss/_variables.scss';

// tooltip cannot use scoped styles

$desktop_diameter: 40px;
$mobile_diameter: 24px;
$mobile_scale: $mobile_diameter / $desktop_diameter;

.icon-button {
  position: relative;
  min-width: $desktop_diameter;
  max-width: $desktop_diameter;
  min-height: $desktop_diameter;
  max-height: $desktop_diameter;
  border-radius: $desktop_diameter / 2;
  overflow: hidden;
  // transition: .2s;
  transition: background .2s !important;

  &.disabled {
    cursor: default;

    & svg {
      fill: $medium_gray;
    }

    &:hover {
      background-color: transparent;
    }
  }

  & > * {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  &_standard-passive,
  &_standard-passive_hover:hover {
    svg {
      fill: $text_color_primary;
    }
  }

  &_standard-hover,
  &_standard-hover_hover:hover {
    background: $layout_border_color;
  }

  &_secondary-passive,
  &_secondary-passive_hover:hover {
    fill: $medium_gray;
  }

  &_secondary-hover,
  &_secondary-hover_hover:hover {
    background: white;
    fill: $text_color_primary;
  }

  &_mobile-passive,
  &_mobile-passive_hover:hover,
  &_mobile-hover,
  &_mobile-hover_hover:hover {
    $mobile_margin_correction: 2; // (0, Inf]

    position: relative;
    transform: scale(.6);
    background: $text_color_primary;
    fill: white;
    margin: -$desktop_diameter * (1 - $mobile_scale) / 2
      / $mobile_margin_correction;
  }

  &_mobile-hover,
  &_mobile-hover_hover:hover {
    transform: scale($mobile_scale * 7 / 6);
  }

  &_active svg {
    fill: url(#icon-gradient-radial);
  }

  &_squared {
    border-radius: $input_border_radius;
  }

  &_bordered {
    border: 1px solid $layout_border_color;
  }
}

.icon-button-tooltip {
  display: block !important;
  z-index: 10000;

  &__inner {
    font-family: 'Gotham Pro', sans-serif;
    font-size: 12px;
    color: white;
    padding: 12px;
    border-radius: 3px;
    background: $text_color_primary;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }

  &__arrow {
    width: 0;
    height: 0;
    border-style: solid;
    position: absolute;
    margin: 5px;
    border-color: $text_color_primary;
    z-index: 1;
  }

  &[x-placement^="top"] {
    margin-bottom: 5px;

    .icon-button-tooltip__arrow {
      border-width: 5px 5px 0 5px;
      border-left-color: transparent !important;
      border-right-color: transparent !important;
      border-bottom-color: transparent !important;
      bottom: -5px;
      left: calc(50% - 5px);
      margin-top: 0;
      margin-bottom: 0;
    }
  }

  &[aria-hidden='true'] {
    visibility: hidden;
    opacity: 0;
    transition: opacity .15s, visibility .15s;
  }

  &[aria-hidden='false'] {
    visibility: visible;
    opacity: 1;
    transition: opacity .15s;
  }
}
</style>
