<template>
  <div :class="containerPaddingClass">
    <div class="track-reviews__filter-container">
      <span
        v-for="period in periodFilterList"
        :key="period"
        class="track-reviews__filter"
      >
        <input
          :id="`track-reviews-filter-${period}`"
          class="track-reviews__filter-input"
          type="radio"
          name="track-reviews-filter"
          :checked="period === periodFilter"
          @change.prevent="onChange($event, period)"
        >
        <label
          :for="`track-reviews-filter-${period}`"
          class="track-reviews__filter-button"
        >
          <CalendarIcon />
          <span class="track-reviews__filter-text">
            {{
              period === 'week'
                ? 'За неделю'
                : period === 'month'
                  ? 'За месяц'
                  : 'За год'
            }}
          </span>
        </label>
      </span>
    </div>

    <!--TODO: search-->

    <TrackReviewsContainer
      :user-id="'me'"
      :commented-in-period="periodFilter"
    />
  </div>
</template>

<script>
import CalendarIcon from 'components/icons/CalendarIcon.vue';
import TrackReviewsContainer from '../TrackReviewsContainer';

export default {
  components: {
    CalendarIcon,
    TrackReviewsContainer
  },

  props: {
    userId: {
      validator: val => (
        typeof value === 'number' || val === 'me'
      ),
      required: true
    },
    defaultPeriod: {
      validator: val => (
        ['week', 'month', 'year'].indexOf(val) !== -1
      ),
      default: 'week'
    }
  },

  data() {
    return {
      periodFilterList: ['week', 'month', 'year'],
      periodFilter: this.defaultPeriod
    };
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  },

  methods: {
    onChange(e, filter) {
      if (e.isTrusted) {
        this.periodFilter = filter;
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackReviews.scss"
/>
