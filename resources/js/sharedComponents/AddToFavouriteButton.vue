<template>
  <IconButton
    :passive="passive"
    :hover="hover"
    :active="isFavourite"
    @press="onPress"
  >
    <HeartIcon/>
  </IconButton>
</template>

<script>
import IconButton, { props as iconButtonProps } from 'components/IconButton.vue';
import HeartIcon from 'components/icons/HeartIcon.vue';
import gql from './gql';

const typeToQueryMap = {
  track: gql.query.TRACK,
  album: gql.query.ALBUM,
  collection: gql.query.COLLECTION,
};

// This component should check if there is favourite list (of the item type) on the client
// and change its length. Also it should update the item itself.

export default {
  components: {
    IconButton,
    HeartIcon
  },

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
    }
  },

  data() {
    return {
      isFavourite: false
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
    }
  },

  methods: {
    onPress() {
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
    },

    addToFavourite() {
      const { itemId, itemType, itemTypeCapital } = this;

      this.$apollo.mutate({
        mutation: gql.mutation.ADD_TO_FAVOURITE,

        variables: {
          type: itemType,
          id: itemId
        },

        update: (store, { data: { addToFavourites } }) => {
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
              userFavourite: true
            }
          }
        }
      }).then(() => {
        console.log(
          'favourite updated:',
          `type: ${itemType};`,
          `id: ${itemId};`,
          `isFav: ${this.isFavourite};`
        );
      }).catch((error) => {
        console.log(error);
      });
    },

    removeFromFavourite() {
      const { itemId, itemType, itemTypeCapital } = this;

      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_FROM_FAVOURITE,

        variables: {
          type: itemType,
          id: itemId
        },

        update: (store, { data: { deleteFromFavourite } }) => {
          store.writeQuery({
            query: gql.query[itemType.toUpperCase()],
            variables: {
              id: itemId
            },
            data: {
              [itemType]: {
                __typename: itemTypeCapital,
                id: itemId,
                userFavourite: false
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
        console.log(
          'favourite updated:',
          `type: ${itemType};`,
          `id: ${itemId};`,
          `isFav: ${this.isFavourite};`
        );
      }).catch((error) => {
        console.log(error);
      });
    }
  },

  apollo: {
    isFavourite() {
      // This query assumes we already have track data
      // regarding to favouritism somewhere in the cache.
      // That way the query is not forced to refetch track data.
      // This approach needs declared cache redirect for 'track'.

      return {
        query: typeToQueryMap[this.itemType],
        variables: {
          id: this.itemId
        },
        update: data => (
          data[this.itemType].userFavourite
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

</style>
