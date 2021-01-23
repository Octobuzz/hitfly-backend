<template>
  <div ref="container" class="app-columns">
    <div ref="left" class="app-columns__left-column">
      <div
        ref="leftInner"
        :class="{'app-columns__left-column-inner': isLeftLocked }"
      >
        <slot
          name="left-column"
          :item-container-class="'app-columns__left-column-item'"
        />

        <!--the hr is needed to tackle with nested elements that has margin-->
        <hr class="app-columns__left-bottom-line">
      </div>
    </div>
    <div ref="right" class="app-columns__right-column">
      <slot
        name="right-column"
        :padding-class="'app-columns__right-column_padding'"
      />
    </div>
  </div>
</template>

<script>
import debounce from 'lodash.throttle';

export default {
  data() {
    return {
      isLeftLocked: false,
      leftHeight: 0,
      containerContentHeight: 0,
      scrollHeight: 0,
      haveSpaceAboveLeft: 0,
      haveSpaceBelowLeft: 0
    };
  },

  watch: {
    isLeftLocked(flag) {
      const { leftInner } = this.$refs;
      const { leftHeight, containerContentHeight } = this;
      const startOffset = this.getElOffset(leftInner).top;

      leftInner.style.transition = 'transform 0s';

      if (flag) {
        leftInner.style.position = 'relative';
        leftInner.style.minHeight = `${containerContentHeight/2}px`;

        const endOffset = this.getElOffset(leftInner).top;
        const offsetDiff = endOffset - startOffset;

        leftInner.style.transform = `translateY(${-offsetDiff}px)`;

        window.setTimeout(() => {
          leftInner.style.transition = 'transform .3s';
          leftInner.style.transform = `translateY(${
            containerContentHeight - Math.max(leftHeight, containerContentHeight)
          }px)`;
        }, 50);

        return;
      }

      leftInner.style.transform = 'translateY(0)';
      leftInner.style.position = 'static';
      leftInner.minHeight = 'auto';

      const endOffset = this.getElOffset(leftInner).top;
      const offsetDiff = endOffset - startOffset;

      leftInner.style.transform = `translateY(${-offsetDiff}px)`;

      window.setTimeout(() => {
        leftInner.style.transition = 'transform .3s';
        leftInner.style.transform = 'translateY(0)';
      }, 50);
    }
  },

  beforeCreate() {
    this.$store.commit('appColumns/setPaddingClass', 'app-columns__right-column_padding');
  },

  mounted() {
    const boundHandleLeft = debounce(this.handleLeft.bind(this), 100);
    const observer = new MutationObserver(boundHandleLeft);

    window.addEventListener('resize', boundHandleLeft);
    window.addEventListener('scroll', boundHandleLeft);

    observer.observe(
      this.$refs.left.children[0],
      {
        attributes: true,
        attributeFilter: ['height', 'min-height'],
        characterData: true,
        subtree: true
      }
    );
    observer.observe(
      this.$refs.right.children[0],
      {
        attributes: true,
        attributeFilter: ['height', 'min-height'],
        characterData: true,
        subtree: true
      }
    );
    boundHandleLeft();

    this.boundHandleLeft = boundHandleLeft;
    this.observer = observer;
  },

  beforeDestroy() {
    window.removeEventListener('resize', this.boundHandleLeft);
    window.removeEventListener('scroll', this.boundHandleLeft);
    this.observer.disconnect();
  },

  methods: {
    getPageHeight() {
      const { body, documentElement: html } = document;

      return Math.max(
        body.scrollHeight,
        body.offsetHeight,
        html.clientHeight,
        html.scrollHeight,
        html.offsetHeight
      );
    },

    getElOffset(el) {
      const rect = el.getBoundingClientRect();
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      return {
        top: rect.top + scrollTop,
        bottom: rect.bottom + scrollTop
      };
    },

    recalculateMetrics() {
      const eps = 10;
      const { container, left } = this.$refs;

      const leftInner = left.children[0];
      const leftClientHeight = leftInner.clientHeight;

      const containerComputedStyle = window.getComputedStyle(container, null);
      const containerPaddingTop = parseFloat(
        containerComputedStyle.getPropertyValue('padding-top')
      );
      const containerPaddingBottom = parseFloat(
        containerComputedStyle.getPropertyValue('padding-bottom')
      );
      const containerContentHeight = window.innerHeight
        - (containerPaddingTop + containerPaddingBottom);

      this.leftHeight = leftClientHeight;
      this.containerContentHeight = containerContentHeight;
      this.scrollHeight = window.pageYOffset + containerContentHeight;

      const leftTopOffset = this.getElOffset(leftInner).top
        - containerPaddingTop;

      const leftLastChildrenBottom = this.getElOffset(leftInner).bottom;

      const leftBottomOffset = Math.abs(
        leftLastChildrenBottom - (this.getPageHeight() - containerPaddingBottom)
      );

      this.haveSpaceAboveLeft = leftTopOffset > eps;
      this.haveSpaceBelowLeft = leftBottomOffset > eps;
    },

    lockLeftToScreenBottom() {
      this.isLeftLocked = true;
    },

    unlockLeftFromScreenBottom() {
      this.isLeftLocked = false;
    },

    handleLeft() {
      this.recalculateMetrics();

      if (
        !this.isLeftLocked
        && !this.haveSpaceAboveLeft
        && this.haveSpaceBelowLeft
        && this.scrollHeight > this.leftHeight
      ) {
        this.lockLeftToScreenBottom();

        return;
      }

      if (
        this.isLeftLocked
        && this.scrollHeight < this.leftHeight
      ) {
        this.unlockLeftFromScreenBottom();
      }
    }
  }
};
</script>

<style lang="scss">
@import '~scss/_variables.scss';

.app-columns {
  display: flex;
  flex-wrap: nowrap;
  align-items: stretch;
  padding: {
    top: $header_height_desktop;
    bottom: $footer_height_desktop;
  }

  &__left-column {
    box-sizing: border-box;
    min-width: 31.8%;
    width: 31.8%;
    min-height: calc(100vh - #{$header_height_desktop} - #{$footer_height_desktop});
    border-right: 1px solid $layout_border_color;
  }

  &__left-column-inner {
    min-width: inherit;
    width: inherit;
    border-right: 1px solid transparent;
  }

  &__left-column-item {
    box-sizing: border-box;
    width: 100%;
    padding: 24px;
    border-bottom: 1px solid $layout_border_color;
  }

  &__right-column {
    box-sizing: border-box;
    flex-grow: 1;
    width: 68.2%;

    &_padding {
      padding: {
        left: 32px;
        right: 32px;
      }
    }
  }

  &__left-bottom-line {
    height: 1px;
    background-color: transparent;
    border: none;
  }
}

@media screen and (max-width: 1024px) {
  .app-columns {
    flex-wrap: wrap;
    height: auto;
    padding-top: $header_height_mobile;

    &__left-column,
    &__right-column {
      width: 100%;
      border: none;
    }

    &__left-column-item {
      padding: {
        left: 32px;
        right: 32px;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .app-columns {
    &__left-column-item {
      padding: 16px;
    }

    &__right-column_padding {
      padding: {
        left: 16px;
        right: 16px;
      }
    }
  }
}
</style>
