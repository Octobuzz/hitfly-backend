<template>
  <IconButton
    ref="iconButton"
    :class="[$attrs.class, 'add-to-favourites-button']"
    :passive="passive"
    :hover="hover"
    :modifier="modifier"
    :active="isFavourite"
    :tooltip="tooltip"
    @press="onPress"
  >
    <span class="add-to-favourites-button__content">
      <HeartIcon />
      <span
        v-if="withCounter"
        class="add-to-favourites-button__counter"
      >
        {{ addedToFavTimes }}
      </span>
    </span>
  </IconButton>
</template>

<script>
import refetchMyFavouriteTracksCount from 'mixins/refetchMyFavouriteTracksCount';
import IconButton, { props as iconButtonProps } from 'components/IconButton.vue';
import HeartIcon from 'components/icons/HeartIcon.vue';
import gql from './gql';

const typeToQueryMap = {
  track: gql.query.TRACK,
  album: gql.query.ALBUM,
  collection: gql.query.COLLECTION,
};

export default {
  components: {
    IconButton,
    HeartIcon
  },

  mixins: [refetchMyFavouriteTracksCount],

  props: {
    ...iconButtonProps,
    itemType: {
      type: String,
      required: true,
      validator(type) {
        return Object.keys(typeToQueryMap).includes(type);
      }
    },
    itemId: {
      type: Number,
      required: true
    },
    fake: {
      type: Boolean,
      default: false
    },
    withCounter: {
      type: Boolean,
      default: false
    },
    tooltip: {
      type: Object,
      required: false
    }
  },

  data() {
    return {
      isFavourite: false,
      addedToFavTimes: 0
    };
  },

  computed: {
    isButtonDisabled: {
      get() {
        return this.$store.getters.favInProcess({
          type: this.itemType,
          id: this.itemId
        });
      },
      set(inProcess) {
        const mutation = inProcess ? 'addFavInProcess' : 'removeFavInProcess';

        this.$store.commit(mutation, {
          type: this.itemType,
          id: this.itemId
        });
      }
    },

    itemTypeCapital() {
      const { itemType: type } = this;

      return type.charAt(0).toUpperCase() + type.slice(1);
    },

    iconButtonWidthPx() {
      const defaultWidth = 40;
      const margin = this.withCounter ? 6 : 0;
      const symbolWidth = this.withCounter ? 8 : 0;

      return defaultWidth + margin + this.addedToFavTimes.toString().length * symbolWidth;
    }
  },

  mounted() {
    const iconButtonStyle = this.$refs.iconButton.$el.style;

    iconButtonStyle.width = `${this.iconButtonWidthPx}px`;
    iconButtonStyle['max-width'] = `${this.iconButtonWidthPx}px`;
  },

  methods: {
    onPress() {
      if (this.fake) {
        this.$emit('press-favourite', this.itemId);

        return;
      }

      if (this.isButtonDisabled) return;

      this.isButtonDisabled = true;

      const {
        isFavourite,
        addToFavourite,
        removeFromFavourite
      } = this;

      if (isFavourite) {
        removeFromFavourite();
      } else {
        addToFavourite();
      }

      this.$emit('press-favourite', this.itemId);
    },

    addToFavourite() {
      const {
        itemId,
        itemType,
        itemTypeCapital,
        addedToFavTimes
      } = this;

      this.$apollo.mutate({
        mutation: gql.mutation.ADD_TO_FAVOURITE,

        variables: {
          type: itemType,
          id: itemId
        },

        update: (store, { data: { addToFavourites } }) => {
          this.refetchMyFavouriteTracksCount();

          if (addToFavourites.id !== -1) {
            this.isButtonDisabled = false;
          }
        },

        optimisticResponse: {
          addToFavourites: {
            __typename: `Favourite${itemTypeCapital}`,
            id: -1,
            [itemType]: {
              __typename: itemTypeCapital,
              id: itemId,
              userFavourite: true,
              favouritesCount: addedToFavTimes + 1
            }
          }
        }
      }).then(() => {
        // handle result if it's needed
      }).catch((error) => {
        console.log(error);
      });
    },

    removeFromFavourite() {
      const {
        itemId,
        itemType,
        itemTypeCapital,
        addedToFavTimes
      } = this;

      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_FROM_FAVOURITE,

        variables: {
          type: itemType,
          id: itemId
        },

        update: (store, { data: { deleteFromFavourite } }) => {
          this.refetchMyFavouriteTracksCount();

          store.writeQuery({
            query: gql.query[itemType.toUpperCase()],
            variables: {
              id: itemId
            },
            data: {
              [itemType]: {
                __typename: itemTypeCapital,
                id: itemId,
                userFavourite: false,
                favouritesCount: addedToFavTimes - 1
              }
            }
          });

          if (deleteFromFavourite === null) {
            this.isButtonDisabled = false;
          }
        },

        optimisticResponse: {
          __typename: 'Mutation',
          deleteFromFavourite: {
            __typename: `Favourite${itemTypeCapital}`,
            id: -1,
            [itemType]: {
              __typename: itemTypeCapital,
              id: itemId,
              userFavourite: false
            }
          }
        }
      }).then(() => {
        // handle result if it's needed
      }).catch((error) => {
        console.log(error);
      });
    }
  },

  apollo: {
    isFavourite() {
      // This query assumes we already have item data
      // regarding to favouritism somewhere in the cache.
      // That way the query is not forced to refetch item data.
      // This approach needs declared cache redirect for the item.

      // eslint-disable-next-line consistent-return
      return {
        query: typeToQueryMap[this.itemType],
        variables() {
          return {
            id: this.itemId
          };
        },
        update: data => (
          data[this.itemType].userFavourite
        ),
        error(error) {
          console.log(error);
        }
      };
    },

    addedToFavTimes() {
      // eslint-disable-next-line consistent-return
      return {
        query: typeToQueryMap[this.itemType],
        variables() {
          return {
            id: this.itemId
          };
        },
        update: data => (
          data[this.itemType].favouritesCount
        ),
        error(error) {
          console.log(error);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
.add-to-favourites-button {
  transition: width 0s;

  &__content {
    width: 100%;
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    align-items: center;
  }

  &__counter {
    font-family: 'Gotham Pro', sans-serif;
    font-size: 13px;
    display: block;
    transform: translateY(2px);
    margin-left: 6px;
  }
}
</style>
