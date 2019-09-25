import debounce from 'lodash.debounce';

// loadMore and hasMoreData expected in component options
// loadOnScrollDisabled is optional (default: false)

export default {
  mounted() {
    window.addEventListener('scroll', this.loadOnScroll);
  },

  destroyed() {
    window.removeEventListener('scroll', this.loadOnScroll);
  },

  methods: {
    loadOnScroll() {
      if (!this.debouncedOnScroll) {
        this.debouncedOnScroll = debounce(() => {
          const { innerHeight, pageYOffset } = window;
          const { scrollHeight } = document.body;

          const maybeLoadMore = Math.abs(
            (innerHeight + pageYOffset) - scrollHeight
          ) <= 250;

          if (maybeLoadMore && this.hasMoreData) {
            this.loadMore();
          }
        });
      }

      if (!this.loadOnScrollDisabled) {
        this.debouncedOnScroll();
      }
    }
  }
};
