<template>
  <div :class="containerPaddingClass">
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

    <!--TODO: TrackReviewsContainer-->
  </div>
</template>

<script>
import CalendarIcon from 'components/icons/CalendarIcon.vue';
import TrackListReviewsContainer from '../TrackListReviewsContainer';

export default {
  components: {
    CalendarIcon,
    TrackListReviewsContainer
  },

  props: {
    // for user-track-list type you should specify user id or string "me"
    // for track type you should specify track id

    forType: {
      validator: val => [
        'user-track-list',
        'music-group-track-list',
        'track'
      ].includes(val),
      required: true
    },
    forId: {
      validator: val => val === 'me' || typeof val === 'number',
      required: true
    },
    defaultPeriod: {
      validator: val => ['week', 'month', 'year'].includes(val),
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
  src="./TrackReviewsInterface.scss"
/>
