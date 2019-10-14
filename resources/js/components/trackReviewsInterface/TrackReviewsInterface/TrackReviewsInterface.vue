<template>
  <div class="track-reviews-interface">
    <div class="track-reviews-interface__filter-container">
      <span
        v-for="period in periodFilterList"
        :key="period"
        class="track-reviews-interface__filter"
      >
        <input
          :id="`track-reviews-filter-${period}`"
          class="track-reviews-interface__filter-input"
          type="radio"
          name="track-reviews-filter"
          :checked="period === periodFilter"
          @change.prevent="onChange($event, period)"
        >
        <label
          :for="`track-reviews-filter-${period}`"
          class="track-reviews-interface__filter-button"
        >
          <CalendarIcon />
          <span class="track-reviews-interface__filter-text">
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

    <TrackListReviewsContainer
      v-if="forType !== 'track'"
      :for-type="forType"
      :for-id="forId"
      :commented-in-period="periodFilter"
    />

    <TrackReviewsContainer
      v-if="forType === 'track'"
      :track-id="forId"
      :commented-in-period="periodFilter"
    />
  </div>
</template>

<script>
import { reviewFilterType, reviewFilterId, commentPeriod } from 'modules/validators';
import CalendarIcon from 'components/icons/CalendarIcon.vue';
import TrackListReviewsContainer from '../TrackListReviewsContainer';
import TrackReviewsContainer from '../TrackReviewsContainer';

export default {
  components: {
    CalendarIcon,
    TrackListReviewsContainer,
    TrackReviewsContainer
  },

  props: {
    forType: {
      validator: reviewFilterType,
      required: true
    },

    forId: {
      validator: reviewFilterId,
      required: true
    },

    defaultPeriod: {
      validator: commentPeriod,
      default: 'week'
    }
  },

  data() {
    return {
      periodFilterList: ['week', 'month', 'year'],
      periodFilter: this.defaultPeriod,
    };
  },

  methods: {
    onChange(e, filter) {
      if (e.isTrusted) {
        this.periodFilter = filter;
      }
    }
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackReviewsInterface.scss"
/>
