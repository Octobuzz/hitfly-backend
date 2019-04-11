<template>
  <div :class="['choose-genres', $attrs.class]">
    <div class="choose-genres__tag-container">
      <BaseTag
        v-for="genre in availableGenres"
        :key="genre.id"
        :name="genre.name"
        :active="selectedGenres
          .some(selected => selected.name === genre.name)"
        class="choose-genres__tag"
        @press="handleTagUpdate"
      />
      <button
        v-if="availableGenres.length < genresMaxCount"
        class="choose-genres__more-tags-button"
        @click="showMoreTags"
      >
        Ещё
      </button>
    </div>

    <slot name="separator" />

    <BaseDropdown
      :value="selectedGenres.map(genre => genre.name)"
      :class="dropdownClass"
      title="Поиск по жанрам"
      :options="availableGenres.map(genre => genre.name)"
      :multiple="true"
      :close-on-select="false"
      :searchable="false"
      :max-height="500"
      @input="handleListUpdate"
    />
  </div>
</template>

<script>
import gql from './gql';
import BaseTag from '../../sharedComponents/BaseTag.vue';
import BaseDropdown from '../../sharedComponents/BaseDropdown.vue';

export default {
  components: {
    BaseTag,
    BaseDropdown
  },

  model: {
    prop: 'selectedGenres'
  },

  props: {
    selectedGenres: {
      type: Array,
      default: () => []
    },
    genresStartCount: {
      type: Number,
      default: 10
    },
    genresMaxCount: {
      type: Number,
      default: 30
    },
    genresQueryCount: {
      type: Number,
      default: 10
    },
    dropdownClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      genres: [],
      genreIdMap: {},
      availableGenresCount: this.genresStartCount,
      showMoreClicked: false
    };
  },

  computed: {
    availableGenres() {
      return this.genres.slice(0, this.availableGenresCount);
    }
  },

  methods: {
    showMoreTags() {
      this.availableGenresCount += this.genresQueryCount;
      this.showMoreClicked = true;
    },

    handleTagUpdate(tag) {
      if (tag.active) {
        this.emitUpdate([
          ...this.selectedGenres,
          this.genreIdMap[tag.name]
        ]);
      } else {
        this.emitUpdate(
          this.selectedGenres
            .filter(selected => selected.name !== tag.name)
        );
      }
    },

    handleListUpdate(selectedTagNames) {
      const update = selectedTagNames
        .map(name => this.genreIdMap[name]);

      this.emitUpdate(update);
    },

    emitUpdate(selectedTags) {
      this.$emit('input', selectedTags);
    }
  },

  apollo: {
    genres: {
      query: gql.query.GENRE_LIST,
      manual: true,
      result({ data: { genre }, networkStatus }) {
        if (networkStatus === 7) {
          this.genres = genre;

          // eslint-disable-next-line no-return-assign
          this.genreIdMap = genre.reduce((acc, genreEntry) => {
            acc[genreEntry.name] = genreEntry;

            return acc;
          }, {});
        }
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../../sass/variables';

.choose-genres {
  &__tag-container {
    display: flex;
    flex-wrap: wrap;
  }

  &__tag {
    margin: {
      right: 8px;
      bottom: 10px;
    }
  }

  &__more-tags-button {
    font-family: "Gotham Pro", serif;
    font-size: 12px;
    line-height: 16px;
    color: $color_3;
    padding: 8px 18px 8px 18px;;
    position: relative;
    margin-bottom: 11px;
    margin-top: auto;
    transform: translateY(1px);
    border-radius: 20px;
    border: 1px solid $color_purple;
    cursor: pointer;
    user-select: none;
    transition: background-color .2s;

    &:hover {
      color: white;
      background: $linear-gradient;
      padding: 9px 19px 9px 19px;
      border: none;
    }
  }
}
</style>
