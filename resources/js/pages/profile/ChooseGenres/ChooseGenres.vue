<template>
  <div :class="['choose-genres', $attrs.class]">
    <div
      :class="[
        'choose-genres__tag-container',
        {
          'choose-genres__tag-container_padded': selectedGenresLength
        }
      ]"
    >
      <BaseTag
        v-for="genre in selectedGenres"
        :id="genre.id"
        :key="genre.id"
        :name="genre.name"
        class="choose-genres__tag"
        @close="removeGenre"
      />
    </div>

    <div
      :class="[
        'choose-genres__dd',
        { 'choose-genres__dd_none-selected-error': noneSelectedError }
      ]"
    >
      <span
        :class="[
          'choose-genres__dd-label',
          {
            'choose-genres__dd-label_top': !ddClosed || !ddEmpty,
          }
        ]"
      >
        Поиск по жанрам
      </span>

      <v-select
        :class="['choose-genres__dd-select', dropdownClass]"
        track-by="id"
        label="name"
        placeholder=""
        :disabled="false"
        :options="availableGenres"
        :searchable="true"
        :preserve-search="true"
        :close-on-select="true"
        @search-change="onSearchChange"
        @open="onOpen"
        @input="onInput"
        @close="onClose"
      >
        <template #noOptions>
          Начните вводить название жанра
        </template>

        <template #noResult>
          Указанного жанра не существует
        </template>
      </v-select>

      <span
        v-if="noneSelectedError"
        class="choose-genres__max-info"
      >
        Минимальное количество жанров: 1
      </span>
      <span v-else-if="selectedGenresLimit !== Infinity" class="choose-genres__max-info">
        Максимальное количество жанров: 5
      </span>
    </div>
  </div>
</template>

<script>
import BaseTag from 'components/BaseTag.vue';
import VSelect from 'vue-multiselect';
import gql from './gql';

export default {
  components: {
    BaseTag,
    VSelect
  },

  model: {
    prop: 'selectedGenres'
  },

  props: {
    selectedGenres: {
      type: Array,
      default: () => []
    },

    selectedGenresLimit: {
      type: Number,
      default: 5
    },

    noneSelectedError: {
      type: Boolean,
      default: false
    },

    // to get dropdownClass option to work we should define class
    // under parent selector with ::v-deep
    dropdownClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      genres: [],
      ddInput: '',
      ddClosed: true,
      ddEmpty: true
    };
  },

  computed: {
    availableGenres() {
      const selected = new Set(this.selectedGenres.map(g => g.id));

      return this.genres.filter(g => !selected.has(g.id));
    },

    selectedGenresLength() {
      return this.selectedGenres.length;
    }
  },

  mounted() {
    this.ddInput = this.$el.querySelector('.multiselect__input');
  },

  methods: {
    onSearchChange() {
      this.ddEmpty = this.ddInput.value === '';
    },

    onInput(genre) {
      if (this.selectedGenres.length >= this.selectedGenresLimit) {
        this.$message(
          `Вы не можете выбрать больше ${this.selectedGenresLimit} жанров`,
          'info',
          { timeout: 2000 }
        );

        return;
      }

      this.emitUpdate([
        ...this.selectedGenres,
        genre
      ]);
    },

    onOpen() {
      this.ddClosed = false;
      this.$emit('open');
    },

    onClose() {
      this.ddClosed = true;

      this.$nextTick(() => {
        if (this.ddInput.value === '') {
          this.ddEmpty = true;
        } else {
          this.ddInput.style.width = '100%';
        }
      });
    },

    removeGenre({ id }) {
      const updatedGenres = this.selectedGenres
        .filter(g => g.id !== id);

      this.emitUpdate(updatedGenres);
    },

    emitUpdate(selectedGenres) {
      this.$emit('input', selectedGenres);
    }
  },

  apollo: {
    genres: {
      query: gql.query.GENRES,
      update({ genre }) {
        return genre;
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./ChooseGenres.scss"
/>
