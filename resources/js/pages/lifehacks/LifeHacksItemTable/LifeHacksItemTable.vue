<template>
  <div ref="root" class="life-hacks-item-table">
    <template v-if="hacks.length">
      <ul class="life-hacks-item-table__table">
        <li
          v-for="hack in hacks"
          :key="hack.id"
          class="life-hacks-item-table__table-item"
          @click="openHack(hack.image)"
        >
          <img
            :src="hack.image.find(({ size }) => size === 'size_300x300').url"
            alt="Лайфхак"
            class="life-hacks-item-table__table-item-img"
          >
        </li>
      </ul>
      <SpinnerLoader
        v-if="hasMoreData"
        class="life-hacks-item-table__table-loader"
        size="small"
      />
    </template>

    <SpinnerLoader
      v-if="!hacks.length && hasMoreData"
      class="life-hacks-item-table__main-loader"
    />

    <p v-if="!hacks.length && !hasMoreData">
      Список лайфхаков пуст
    </p>

    <BaseModal
      :open.sync="showModal"
      :auto-close="true"
      :container-style="{
        position: 'relative',
        lineHeight: 0,
        padding: 0
      }"
      bg-effect="whiten"
    >
      <span
        class="life-hacks-item-table__cross-icon"
        @click="closeHack"
      />
      <img
        :class="[
          'life-hacks-item-table__table-item-img',
          'life-hacks-item-table__table-item-img_big'
        ]"
        :src="hackImgFullRes"
        alt="Лайфхак"
      >
    </BaseModal>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import elHasSpaceBelow from 'modules/elHasSpaceBelow';
import loadOnScroll from 'mixins/loadOnScroll';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import BaseModal from 'components/BaseModal.vue';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    BaseModal
  },

  mixins: [loadOnScroll],

  data() {
    return {
      hacks: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 6,
        filters: {
          tag: null
        }
      },
      hackImgFullRes: '',
      showModal: false
    };
  },

  computed: {
    ...mapGetters({
      selectedCategoryId: 'selectedLifeHacksCategoryId'
    })
  },

  watch: {
    selectedCategoryId(id) {
      this.queryVars = {
        pageNumber: 1,
        pageLimit: 6,
        filters: {
          tag: id
        }
      };
    }
  },

  mounted() {
    this.$refs.root.addEventListener('scroll', this.loadOnScroll);
  },

  beforeDestroy() {
    this.$refs.root.removeEventListener('scroll', this.loadOnScroll);
  },

  methods: {
    fetchMoreHacks(vars) {
      return this.$apollo.queries.hacks.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { lifehack } }) => {
          const { total, to, data: newLifeHacks } = lifehack;

          return {
            tag: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tag.__typename,
              total,
              to,
              data: [...currentList.tag.data, ...newLifeHacks]
            }
          };
        },
      });
    },

    loadMore() {
      if (this.isLoading) return;

      this.isLoading = true;

      this.fetchMoreHacks({
        ...this.queryVars,
        pageNumber: this.queryVars.pageNumber + 1
      })
        .then(() => {
          this.queryVars.pageNumber += 1;
          this.isLoading = false;
        })
        .catch((err) => {
          console.dir(err);
        });
    },

    attemptToLoadMore() {
      if (elHasSpaceBelow(this.$refs.root, 350)) {
        this.loadMore();
      } else {
        this.loadOnScroll();
      }
    },

    openHack(img) {
      this.showModal = true;
      this.hackImgFullRes = img.find(({ size }) => size === 'size_800x800').url;
    },

    closeHack() {
      this.showModal = false;
    }
  },

  apollo: {
    hacks: {
      client: 'public',
      fetchPolicy: 'network-only',
      query: gql.query.LIFE_HACK_LIST,
      variables() {
        return this.queryVars;
      },
      update({ lifehack: { total, to, data } }) {
        this.isLoading = false;
        this.hasMoreData = to < total;

        // check if the screen has empty space to load more hacks

        this.$nextTick(() => {
          if (!this.hasMoreData) return;

          const componentRoot = this.$refs.root;
          const mounted = componentRoot !== undefined;

          if (!mounted) {
            this.$once('hook:mounted', this.attemptToLoadMore.bind(this));

            return;
          }
          this.attemptToLoadMore();
        });

        return data;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return this.selectedCategoryId === null;
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./LifeHacksItemTable.scss"
/>
