<template>
  <div
    ref="root"
    :class="['favourite-track-list', containerPaddingClass]"
  >
    <ReturnHeader class="favourite-track-list__return-button" />

    <template v-if="shownTrack">
      <span class="favourite-track-list__singer">
        {{ shownTrack.singer }}
      </span>

      <div class="favourite-track-list__track-section">
        <img
          :src="
            shownTrack.cover.find(
              cover => cover.size === 'size_150x150'
            ).url
          "
          alt="Track cover"
          class="favourite-track-list__track-cover"
        >

        <div class="favourite-track-list__player-section">
          <span class="h3 favourite-track-list__list-title">
            Любимые песни
          </span>

          <div class="favourite-track-list__player" />
        </div>
      </div>

      <div class="favourite-track-list__button-section">
        <button
          @click="playFavTracks"
          :class="[
            'favourite-track-list__button',
            'favourite-track-list__button_listen'
          ]"
        >
          <template v-if="isFavTrackListPlaying && playerIsPlaying">
            <CirclePauseIcon />
            Пауза
          </template>
          <template v-else>
            <CirclePlayIcon />
            Слушать
          </template>
        </button>

        <AddToFavButton
          ref="addToFavButton"
          class="favourite-track-list__button"
          passive="standard-passive"
          hover="standard-hover"
          modifier="squared bordered"
          item-type="track"
          :item-id="shownTrack.id"
        />

        <TrackActionsPopover
          :track-id="shownTrack.id"
          :show-remove-option="false"
          @press-favourite="onPressFavourite"
        >
          <IconButton
            class="favourite-track-list__button favourite-track-list__button_more"
            passive="standard-passive"
            hover="standard-hover"
            modifier="squared bordered"
            :tooltip="tooltip.more"
          >
            <DotsIcon />
          </IconButton>
        </TrackActionsPopover>
      </div>
    </template>

    <TrackList
      v-if="trackIdList.length > 0"
      :track-id-list="trackIdList"
      :show-remove-button="false"
      :show-table-header="false"
      :show-album-section="false"
    >
      <template v-if="hasMoreData" #loader>
        <SpinnerLoader
          class="favourite-track-list__loader"
          size="small"
        />
      </template>
    </TrackList>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import elHasSpaceBelow from 'modules/elHasSpaceBelow';
import containerPaddingClass from 'mixins/containerPaddingClass';
import playingTrackId from 'mixins/playingTrackId';
import loadOnScroll from 'mixins/loadOnScroll';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackList from 'components/trackList/TrackList';
import IconButton from 'components/IconButton.vue';
import CirclePlayIcon from 'components/icons/CirclePlayIcon.vue';
import CirclePauseIcon from 'components/icons/CirclePauseIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import AddToFavButton from 'components/AddToFavouriteButton';
import TrackActionsPopover from 'components/trackList/TrackActionsPopover';
import ReturnHeader from '../ReturnHeader.vue';
import gql from './gql';

const ofNumber = arg => typeof arg === 'number';

export default {
  components: {
    SpinnerLoader,
    TrackList,
    IconButton,
    CirclePlayIcon,
    CirclePauseIcon,
    DotsIcon,
    AddToFavButton,
    TrackActionsPopover,
    ReturnHeader
  },

  mixins: [
    loadOnScroll,
    containerPaddingClass,
    playingTrackId
  ],

  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 15
      },
      playingTrack: null,
      firstTrack: null,
      firstTrackId: null,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
    trackIdList() {
      return this.trackList.map(track => track.id);
    },

    shownTrack() {
      const {
        trackIdList,
        playingTrack,
        firstTrack
      } = this;

      if (!firstTrack) {
        return null;
      }

      if (playingTrack && trackIdList.includes(playingTrack.id)) {
        return playingTrack;
      }

      return firstTrack;
    },

    isFavTrackListPlaying() {
      return this.playerCurrentType.type === 'favourite-tracks';
    },

    ...mapGetters(['profile/myId']),

    ...mapGetters('player', {
      playerCurrentType: 'getCurrentType',
      playerIsPlaying: 'isPlaying'
    })
  },

  methods: {
    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { favouriteTrack } }) => {
          const { total, to, data: newTracks } = favouriteTrack;

          return {
            favouriteTrack: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.favouriteTrack.__typename,
              total,
              to,
              data: [...currentList.favouriteTrack.data, ...newTracks]
            }
          };
        },
      });
    },

    loadMore() {
      if (this.isLoading) return;

      this.isLoading = true;

      this.fetchMoreTracks({
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

    onPressFavourite() {
      this.$refs.addToFavButton.$el.dispatchEvent(new Event('click'));
    },

    playFavTracks() {
      const {
        playerIsPlaying,
        isFavTrackListPlaying
      } = this;

      if (playerIsPlaying && isFavTrackListPlaying) {
        this.playerPausePlaying();

        return;
      }

      if (isFavTrackListPlaying) {
        this.playerStartPlaying();

        return;
      }

      this.$apollo.query({
        client: this.apolloClient,
        query: gql.query.QUEUE_FAVOURITE_TRACKS,
        variables: {
          pageLimit: 30,
          pageNumber: 1
        },
        error(err) {
          console.dir(err);
        }
      })
        .then((res) => {
          const favTracks = res.data.favouriteTrack.data.map(
            dataEntry => dataEntry.track
          );
          const nextPlayerType = {
            type: 'favourite-tracks',
            id: null
          };

          this.playerPausePlaying();
          this.playerChangeCurrentType(nextPlayerType);
          this.playerPickTrack(favTracks[0]);

          const favTrackIdList = favTracks.map(track => track.id);
          this.playerPickPlaylist(favTrackIdList);
        })
        .catch((err) => {
          console.dir(err);
        });
    },

    ...mapMutations('player', {
      playerStartPlaying: 'startPlaying',
      playerPausePlaying: 'pausePlaying',
      playerPickTrack: 'pickTrack',
      playerPickPlaylist: 'pickPlaylist',
      playerChangeCurrentType: 'changeCurrentType'
    })
  },

  apollo: {
    trackList: {
      client: 'private',
      fetchPolicy: 'network-only',
      query: gql.query.FAVOURITE_TRACKS,
      variables() {
        return this.queryVars;
      },
      update({ favouriteTrack: { total, to, data } }) {
        this.isLoading = false;
        this.hasMoreData = to < total;
        this.firstTrackId = +data[0].track.id;

        // check if the screen has empty space to load more comments

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

        return data.map(entry => entry.track);
      },
      error(err) {
        console.dir(err);
      }
    },

    playingTrack: {
      client: 'private',
      query: gql.query.TRACK,
      variables() {
        return {
          id: this.playingTrackId
        };
      },
      update({ track }) {
        return track;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return !ofNumber(this.playingTrackId);
      }
    },

    firstTrack: {
      client: 'private',
      query: gql.query.TRACK,
      variables() {
        return {
          id: this.firstTrackId
        };
      },
      update({ track }) {
        return track;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return !ofNumber(this.firstTrackId);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./FavouriteTrackList.scss"
/>
