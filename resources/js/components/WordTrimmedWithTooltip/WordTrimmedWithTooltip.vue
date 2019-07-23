<template>
  <span
    ref="wordRef"
    v-tooltip="tooltipOptions"
    :class="[
      'word-trimmer-with-tooltip__word',
      $attrs.class
    ]"
    @mouseenter="calcWidthAndSetShown"
    @mouseleave="unsetShown"
  >
    {{ word }}
    <span
      v-if="showChecker"
      ref="checkerRef"
      class="word-trimmer-with-tooltip__checker"
    >
      {{ word }}
    </span>
  </span>
</template>

<script>
export default {
  props: {
    word: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      shown: false,
      wordRef: null,
      checkerRef: null,
      wordWidth: 0,
      checkerWidth: 0,
      showChecker: false
    };
  },

  computed: {
    trimmed() {
      return this.wordWidth < this.checkerWidth;
    },

    tooltipOptions() {
      return {
        trigger: 'manual',
        show: this.shown && this.trimmed,
        autoHide: false,
        content: this.word,
        template: `
          <div class="word-trimmer-with-tooltip__tooltip" role="tooltip">
            <div class="word-trimmer-with-tooltip__arrow"></div>
            <div class="word-trimmer-with-tooltip__inner"></div>
          </div>
        `,
        classes: 'word-trimmer-with-tooltip__tooltip',
        innerSelector: '.word-trimmer-with-tooltip__inner',
        arrowSelector: '.word-trimmer-with-tooltip__arrow',
        placement: 'top',
        hideOnTargetClick: false,
        popperOptions: {
          modifiers: {
            preventOverflow: {
              enabled: true,
              padding: 20
            }
          }
        }
      };
    }
  },

  methods: {
    setShown() {
      if (this.shown || this.shownScheduled) {
        return;
      }

      this.shownScheduled = setTimeout(() => {
        this.shown = true;
      }, 300);
    },

    unsetShown() {
      if (this.shownScheduled) {
        clearTimeout(this.shownScheduled);
        this.shownScheduled = null;
      }
      this.shown = false;
    },

    calcWidth() {
      this.wordRef = this.$refs.wordRef;
      this.checkerRef = this.$refs.checkerRef;

      this.wordWidth = this.wordRef.getBoundingClientRect().width;
      this.checkerWidth = this.checkerRef.getBoundingClientRect().width;
    },

    calcWidthAndSetShown() {
      this.showChecker = true;

      this.$nextTick(() => {
        this.calcWidth();
        this.setShown();
        this.showChecker = false;
      });
    }
  }
};
</script>

<style
  lang="scss"
  src="./WordTrimmedWithTooltip.scss"
/>
