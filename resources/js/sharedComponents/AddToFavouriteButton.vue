<template>
  <IconButton
    :passive="passive"
    :hover="hover"
    :active="isItemFavourite"
    @press="onPress"
  >
    <HeartIcon/>
  </IconButton>
</template>

<script>
import IconButton, { props as iconButtonProps } from 'components/IconButton.vue';
import HeartIcon from 'components/icons/HeartIcon.vue';
import gql from './gql';

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
        return ['track', 'album', 'collection'].includes(type);
      }
    },
    itemId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      isItemFavourite: false
    };
  },

  computed: {
    computedQuery() {
      const possibilities = {
        track: gql.query.TRACK,
        album: gql.query.ALBUM,
        collection: gql.query.COLLECTION,
      };

      return possibilities[this.itemType];
    },

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
    }
  },

  methods: {
    onPress() {
      if (this.isButtonDisabled) return;

      this.isButtonDisabled = true;

      const {
        isItemFavourite,
        addToFavourite,
        removeFromFavourite
      } = this;

      if (isItemFavourite) {
        removeFromFavourite();
      } else {
        addToFavourite();
      }
    },

    addToFavourite() {
      this.$apollo.mutate({
        mutation: gql.mutation.ADD_TO_FAVOURITE,
        variables: {
          type: this.itemType,
          id: this.itemId
        },
        update: (store, { data: { addToFavourites } }) => {
          this.isButtonDisabled = false;

          // todo: update fav list
        },
        // optimisticResponse: {
        //   __typename: 'Mutation',
        //   addToFavourites: {
        //     track: {
        //       __typename: 'Track',
        //       ...this.track,
        //       userFavourite: true
        //     },
        //     id: -1,
        //     __typename: 'FavouriteTrack',
        //   }
        // }
      }).then(() => {
        console.log(
          'favourite updated:',
          `type: ${this.itemType};`,
          `id: ${this.itemId};`,
          `isFav: ${this.isItemFavourite};`
        );
      }).catch((error) => {
        console.log(error);
      });
    },

    removeFromFavourite() {
      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_FROM_FAVOURITE,
        variables: {
          type: this.itemType,
          id: this.itemId
        },
        update: (store, { data: { deleteFromFavourite } }) => {
          this.isButtonDisabled = false;
          // todo: cause backend returns null we handle it manually, do null check!!!

          // eslint-disable-next-line no-underscore-dangle
          const __typename = this.itemType.charAt(0).toUpperCase()
            + this.itemType.slice(1);

          store.writeQuery({
            query: gql.query[this.itemType.toUpperCase()],
            variables: {
              id: this.itemId
            },
            data: {
              [this.itemType]: {
                __typename,
                id: this.itemId,
                userFavourite: false
              }
            }
          });

          // todo: update fav track list
        },
        // optimisticResponse: {
        //   deleteFromFavourite: {
        //     track: {
        //       ...this.track,
        //       userFavourite: false,
        //       __typename: 'Track',
        //     },
        //     id: -1,
        //     __typename: 'FavouriteTrack',
        //   },
        //   __typename: 'Mutation',
        // }
      }).then(() => {
        console.log(
          'favourite updated:',
          `type: ${this.itemType};`,
          `id: ${this.itemId};`,
          `isFav: ${this.isItemFavourite};`
        );
      }).catch((error) => {
        console.log(error);
      });
    }
  },

  apollo: {
    isItemFavourite() {
      return {
        query: this.computedQuery,
        variables: {
          id: this.itemId
        },
        update: (data) => {
          return data[this.itemType].userFavourite;
        },
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
