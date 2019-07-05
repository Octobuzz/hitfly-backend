<template>
  <div class="universal-tracks-container">
    <slot
      name="default"
      :track-id-list="trackIdList"
    />
    <SpinnerLoader
      v-if="hasMoreData && trackIdList.length < shownTracksCount"
      class="universal-tracks-container__loader"
      size="small"
    />
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import gql from './gql';

// Due to loadMore blocking ability to remove more tracks
// the separation was implemented.

export default {
  components: {
    SpinnerLoader
  },

  props: {
    forType: {
      validator: val => [
        'user',
        'music-group',
        'album',
        'collection'
      ].includes(val),
      required: true
    },
    forId: {
      validator: val => typeof val === 'number' || 'me',
      required: true
    },
    shownTracksCount: {
      type: Number,
      default: Infinity
    }
  },

  data() {
    const { forType, forId } = this;
    const filters = {};
    const mutationPartialArgs = {};
    let mutation;

    // eslint-disable-next-line default-case
    switch (forType) {
      case 'user':
        if (forId === 'me') {
          filters.my = true;
        } else {
          filters.userId = forId;
        }

        // actually we can only remove track for 'me' thus
        // no mutation args are provided
        mutation = gql.mutation.REMOVE_TRACK;
        break;

      // TODO: mutation, mutationPartialArgs for music-group
      case 'music-group':
        filters.musicGroupId = forId;
        break;

      case 'album':
        filters.albumId = forId;
        mutation = gql.mutation.REMOVE_TRACK_FROM_ALBUM;
        mutationPartialArgs.albumId = forId;
        break;

      case 'collection':
        filters.collectionId = forId;
        mutation = gql.mutation.REMOVE_TRACK_FROM_COLLECTION;
        mutationPartialArgs.collectionId = forId;
        break;
    }

    return {
      trackList: [],
      isLoading: false,
      shouldLoadMore: false,
      removesInProcess: 0,
      pendingRemoves: [],
      removedCount: 0,
      hasMoreData: true,
      initialFetchDone: false,
      queryVars: {
        pageNumber: 1,
        pageLimit: 15,
        filters
      },
      mutation,
      mutationPartialArgs
    };
  },

  computed: {
    trackIdList() {
      return this.trackList
        .map(track => track.id)
        .slice(0, this.shownTracksCount);
    }
  },

  watch: {
    removesInProcess(count) {
      if (count === 0 && this.shouldLoadMore) {
        this.loadMore();
      }
    },

    isLoading(inProcess) {
      if (!inProcess && this.pendingRemoves.length > 0) {
        this.pendingRemoves.forEach(id => this.removeTrack(id));
        this.pendingRemoves = [];
      }
    }
  },

  methods: {
    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { tracks } }) => {
          const { total, to, data } = tracks;

          // TODO: possible optimization for large data set

          const newTracks = data.filter(newTrack => (
            !currentList.tracks.data.some(
              currentTrack => currentTrack.id === newTrack.id
            )
          ));

          return {
            tracks: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tracks.__typename,
              total,
              to,
              data: [
                ...currentList.tracks.data,
                ...newTracks
              ]
            }
          };
        },
      });
    },

    loadMore() {
      if (!this.hasMoreData
          || this.isLoading
          || this.removesInProcess > 0) {
        return;
      }

      this.isLoading = true;

      const pageNumber = (this.queryVars.pageNumber + 1)
        - Math.ceil(this.removedCount / this.queryVars.pageLimit);

      this.fetchMoreTracks({
        ...this.queryVars,
        pageNumber
      })
        .then(() => {
          this.queryVars.pageNumber += 1;
          this.isLoading = false;
          this.shouldLoadMore = false;
        })
        .catch((err) => {
          console.dir(err);
        });
    },

    removeTrack(id) {
      this.removesInProcess += 1;

      const {
        forType,
        forId,
        mutation,
        mutationPartialArgs
      } = this;

      const variables = forType === 'user' && forId === 'me'
        ? { id }
        : { ...mutationPartialArgs, trackId: id };

      // TODO: mutation name for music-group
      // eslint-disable-next-line consistent-return
      const getMutationName = () => {
        // eslint-disable-next-line default-case
        switch (mutation) {
          case gql.mutation.REMOVE_TRACK:
            return 'deleteTrackMutation';

          case gql.mutation.REMOVE_TRACK_FROM_ALBUM:
            return 'removeTrackFromAlbum';

          case gql.mutation.REMOVE_TRACK_FROM_COLLECTION:
            return 'removeTrackFromCollection';
        }
      };

      const optimisticResponse = {
        __typename: 'Mutation',
        [getMutationName()]: {
          __typename: 'Track',
          id
        }
      };

      this.$apollo.mutate({
        mutation,
        variables,
        optimisticResponse,

        update: (store) => {
          const { tracks } = store.readQuery({
            query: gql.query.TRACKS,

            // cause updateQuery does not update variables in cache entry
            // we always refer to the first page

            variables: {
              ...this.queryVars,
              pageNumber: 1
            }
          });

          const updatedTracks = {
            tracks: {
              ...tracks,
              data: [
                ...tracks.data.filter(track => track.id !== id)
              ]
            }
          };

          store.writeQuery({
            query: gql.query.TRACKS,
            variables: {
              ...this.queryVars,
              pageNumber: 1
            },
            data: updatedTracks
          });
        }
      })
        .then(() => {
          this.removesInProcess -= 1;
          this.removedCount += 1;
        })
        .catch((err) => {
          this.removesInProcess -= 1;

          this.$message(
            'Произошла ошибка. Трек не был удален',
            'info',
            { timeout: 2000 }
          );

          console.dir(err);
        });
    },

    // call this from outside of the component
    callLoadMore() {
      if (this.removesInProcess > 0) {
        this.shouldLoadMore = true;
      } else {
        this.loadMore();
      }
    },

    // call this from outside of the component
    callRemoveTrack(id) {
      if (this.isLoading) {
        this.pendingRemoves.push(id);
      } else {
        this.removeTrack(id);
      }
    }
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.TRACKS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ tracks: { total, to, data } }) {
          if (this.initialFetchDone === false) {
            this.initialFetchDone = true;
            this.$emit('initialized', {
              data,
              success: true,
              error: null
            });
          }

          this.$emit('data');
          this.hasMoreData = to < total;

          return data;
        },

        error(err) {
          // TODO: error message

          if (this.initialFetchDone === false) {
            this.initialFetchDone = true;
            this.$emit('initialized', {
              success: false,
              error: err
            });
          }

          console.dir(err);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UniversalTracksContainer.scss"
/>
