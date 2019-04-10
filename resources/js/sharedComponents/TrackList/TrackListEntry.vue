<template>
  <div class="track-list-entry">
    <span class="track-list-entry__index">
      {{ index }}
    </span>

    <button
      class="track-list-entry__album"
      @click="onAlbumPress"
    >
      <img
        :src="track.album.cover"
        alt="Album cover"
      >
    </button>

    <!--TODO: shared like button-->
    <button
      style="transition: all 0s;"
      :class="['heart', { 'favourite': track.userFavourite }]"
      @click="onFavouriteClick"
    >
      like
    </button>

    {{ track.album.author }}

    <!--TODO: implement track playlist id on the backend-->

    <TrackToPlaylistPopover :track="track">
      <button>
        {{ track.trackName }}
      </button>
    </TrackToPlaylistPopover>

    <TrackActions :track="track">
      <button>
        track actions
      </button>
    </TrackActions>
  </div>
</template>

<script>
import gql from './gql';
import TrackToPlaylistPopover from './TrackToPlaylistPopover.vue';
import TrackActions from './TrackActions.vue';

function updateFavTrackList(store, track, add) {
  try {
    const { favouriteTrack: favTrackList } = store.readQuery({
      query: gql.query.FAVOURITE_TRACK_LIST
    });

    if (add) {
      favTrackList.data = [
        {
          track: {
            ...track,
            __typename: 'Track',
          },
          __typename: 'FavouriteTrack',
        },
        ...favTrackList.data
      ];
    } else {
      favTrackList.data = favTrackList.data
        .filter(favTrack => favTrack.track.id !== track.id);
    }

    store.writeQuery({
      query: gql.query.FAVOURITE_TRACK_LIST,
      data: {
        favouriteTrack: favTrackList
      }
    });
  } catch (err) {
    if (err.message.indexOf('Can\'t find field') !== -1) {
      console.log('favourite track list was not fetched');
    } else {
      console.log(err);
    }
  }
}

export default {
  components: {
    TrackToPlaylistPopover,
    TrackActions
  },

  props: {
    track: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  },

  data() {
    return {};
  },

  methods: {
    onAlbumPress() {
      console.log('album pressed');
    },

    onFavouriteClick() {
      if (this.track.userFavourite) {
        this.removeFromFavourites();
      } else {
        this.addToFavourites();
      }
    },

    addToFavourites() {
      this.$apollo.mutate({
        mutation: gql.mutation.ADD_TO_FAVOURITES,
        variables: {
          id: this.track.id,
        },
        update: (store, { data: { addToFavourites: { track } } }) => {
          // 'removing favourite is in process' variable

          updateFavTrackList(store, track, true);
        },
        optimisticResponse: {
          __typename: 'Mutation',
          addToFavourites: {
            track: {
              __typename: 'Track',
              ...this.track,
              userFavourite: true
            },
            id: -1,
            __typename: 'FavouriteTrack',
          }
        }
      }).then(() => {
        console.log('favourite updated, id:', this.track.id, 'isFav:', this.track.userFavourite);
      }).catch((error) => {
        console.log(error);
      });
    },

    removeFromFavourites() {
      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_FROM_FAVOURITES,
        variables: {
          id: this.track.id,
        },
        update: (store, { data: { deleteFromFavourite } }) => {
          // 'adding favourite is in process' variable

          // TODO: this is a fix until backend will be returning data on the delete query
          store.writeQuery({
            query: gql.query.TRACK,
            variables: {
              id: this.track.id
            },
            data: {
              track: {
                ...this.track,
                userFavourite: false
              }
            }
          });

          updateFavTrackList(store, { ...this.track, userFavourite: false }, false);
        },
        optimisticResponse: {
          deleteFromFavourite: {
            track: {
              ...this.track,
              userFavourite: false,
              __typename: 'Track',
            },
            id: -1,
            __typename: 'FavouriteTrack',
          },
          __typename: 'Mutation',
        }
      }).then(() => {
        console.log('favourite updated, id:', this.track.id, 'isFav:', this.track.userFavourite);
      }).catch((error) => {
        console.log(error);
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
.track-list-entry {
  display: flex;
  align-items: center;
  height: 56px;

  &__index {
    display: flex;
    justify-content: center;
    width: 16px;
    margin-right: 8px;
  }

  &__album {
    width: 32px;
    height: 32px;
    position: relative;
    margin-right: 8px;
    border-radius: 0;
    overflow: hidden;

    background: #00c0ef; // temp

    &_paused:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 100;
      background: black;
      opacity: .5;
    }
  }
}

.heart {
  background: orange;
}

.favourite {
  background: cornflowerblue;
}
</style>
