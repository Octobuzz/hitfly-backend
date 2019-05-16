<template>
  <div :class="['choose-location', $attrs.class]">
    <span
      :class="[
        'choose-location__label',
        {
          'choose-location__label_top': !closed || !empty,
        }
      ]"
    >
      Город
    </span>

    <span class="choose-location__icon-container">
      <BalloonIcon/>
    </span>

    <v-select
      class="choose-location__select"
      track-by="id"
      placeholder=""
      :value="value"
      :options="availableLocations"
      :disabled="false"
      :searchable="true"
      :internal-search="false"
      :loading="isLoading"
      :show-labels="false"
      :close-on-select="true"
      @open="onOpen"
      @search-change="fetchData"
      @input="emitInput"
      @close="onClose"
    >
      <template #singleLabel="{ option: { title, area_region } }">
        <template v-if="title">
          <span>
            {{ title }}
          </span>
          <span class="choose-location__secondary-text">
            {{ area_region }}
          </span>
        </template>
      </template>

      <template #option="{ option: { title, area_region } }">
        <span>
          <span>
            {{ title }}
          </span>
          <span class="choose-location__secondary-text">
            {{ area_region }}
          </span>
        </span>
      </template>

      <template #noOptions>
        Начните вводить название населенного пункта
      </template>

      <template #noResult>
        Указанного населенного пункта нет в базе
      </template>
    </v-select>
  </div>
</template>

<script>
import debounce from 'lodash.debounce';
import VSelect from 'vue-multiselect';
import BalloonIcon from 'components/icons/BalloonIcon.vue';
import gql from './gql';

export default {
  components: {
    VSelect,
    BalloonIcon
  },

  props: {
    value: {
      type: Object,
      default: () => {}
    },
  },

  data() {
    return {
      input: null,
      availableLocations: [],
      isLoading: false,
      closed: true,
      empty: true
    };
  },

  created() {
    this.debouncedFetchData = debounce((query) => {
      this.$apollo.query({
        query: gql.query.LOCATIONS,
        variables: {
          query,
          limit: 25,
          page: 1
        },
        fetchPolicy: 'no-cache'
      })
        .then(({ data: { locations } }) => {
          this.isLoading = false;
          this.availableLocations = locations.data;
        })
        .catch(
          err => console.log(err)
        );
    }, 100);
  },

  mounted() {
    this.input = this.$el.querySelector('.multiselect__input');
    this.redefineEmpty();
  },

  updated() {
    this.redefineEmpty();
  },

  methods: {
    redefineEmpty() {
      this.empty = !this.value.title;
    },

    onOpen() {
      this.closed = false;
      this.availableLocations = [];
    },

    onClose() {
      this.closed = true;
      this.availableLocations = [];
      if (this.input.value === '') {
        this.emitInput(null);
      }
    },

    fetchData(query) {
      this.isLoading = true;
      this.debouncedFetchData(query);
    },

    emitInput(e) {
      this.$emit('input', { ...e });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./ChooseLocation.scss"
/>
