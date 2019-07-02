<template>
  <div>
    <UniversalTracksContainer
      ref="tracksContainer"
      :for-type="forType"
      :for-id="forId"
      :shown-tracks-count="shownTracksCount"
      @initialized="onTracksContainerInitialized"
    >
      <template #default="container">
        <TrackList
          v-if="container.trackIdList.length > 0"
          :track-id-list="container.trackIdList"
          :show-remove-button="showRemoveButton"
          @remove-track="onRemoveTrack"
        >
          <template #header>
            <slot name="header" />
          </template>
        </TrackList>
      </template>
    </UniversalTracksContainer>
  </div>
</template>

<script>
import loadOnScroll from 'mixins/loadOnScroll';
import TrackList from 'components/trackList/TrackList';
import UniversalTracksContainer from 'components/UniversalTracksContainer';

export default {
  components: {
    TrackList,
    UniversalTracksContainer
  },

  mixins: [loadOnScroll],

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
    },
    showRemoveButton: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {
      initialFetchDone: false,
    };
  },

  computed: {
    tracksContainer() {
      return this.$refs.tracksContainer;
    },
    hasMoreData() {
      return this.tracksContainer.hasMoreData;
    },
    loadOnScrollDisabled() {
      const {
        initialFetchDone,
        shownTracksCount,
        tracksContainer
      } = this;

      return !initialFetchDone
        || shownTracksCount <= tracksContainer.trackIdList.length;
    }
  },

  mounted() {
    this.tracksContainer.$on('data', () => {
      this.$nextTick(() => {
        this.loadOnScroll();
      });
    });
  },

  methods: {
    onTracksContainerInitialized(init) {
      this.$emit('initialized', init.data);

      if (init.success) {
        this.initialFetchDone = true;
        this.$nextTick(() => {
          this.loadOnScroll();
        });
      }
    },
    onRemoveTrack(trackId) {
      this.tracksContainer.callRemoveTrack(trackId);
    },
    loadMore() {
      this.tracksContainer.callLoadMore();
    }
  }
};
</script>
