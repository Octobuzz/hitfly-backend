<template>
  <footer :class="['footer', $attrs.class]">
    <div class="footer__left footer-info">
      <img class="footer-info__img" :src="currentTrack.cover[0].url" style="width: 40px; height: 40px;" v-if="!emptyTrack" />
      <div class="footer-info__text" v-if="!emptyTrack">
        <a class="footer-info__song">{{ currentTrack.trackName }}</a>
        <a class="footer-info__author">{{ currentTrack.singer }}</a>
      </div>
    </div>
    <div class="footer__center">

      <div class="footer-play">
        <span @click="switchTrack('prev')">
          <IconButton :class="{ disabled: emptyTrack }">
            <PlayPreviousIcon />
          </IconButton>
        </span>

        <span @click="startPause">
          <IconButton :class="{ footer__playButton: !playing, disabled: emptyTrack }">
            <PlayIcon v-if="!playing" />
            <PauseIcon v-else />
          </IconButton>
        </span>

        <span @click="switchTrack('next')">
          <IconButton :class="{ disabled: emptyTrack }">
            <PlayNextIcon />
          </IconButton>
        </span>
      </div>

      <div class="progress" v-if="!emptyTrack">
        <div class="progress__bar progress__text">
          <div class="progress__time" v-if="fixedTime">{{ fixedTime }}</div>
          <div class="progress__time" v-else>00:00</div>
          <div class="progress__time" v-if="currentLength">-{{ currentLength }}</div>
          <div class="progress__time" v-else>--:--</div>
        </div>
        <div class="progress__bar progress__progress" @click="seek">
          <div class="progress__line" :style="{ width: percentComplete }">
            <div class="progress__head"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer__right">

      <span>
        <TrackToPlaylistPopover
          :isFooter="true"
          v-if="!emptyTrack && desktop"
          :track-id="this.$store.getters['player/currentTrack'].id"
        >
          <IconButton
            :tooltip="tooltip.add"
          >
            <PlusIcon />
          </IconButton>
        </TrackToPlaylistPopover>
      </span>

      <AddToFavouriteButton
        v-if="!emptyTrack"
        item-type="track"
        :item-id="currentTrack.id"
        :with-counter="true"
        :tooltip="tooltip.like"
      />

      <span @click="toggleLoop = !toggleLoop">
        <IconButton
        v-if="!emptyTrack"
        :active="toggleLoop"
        :tooltip="tooltip.loop"
        >
          <LoopIcon />
        </IconButton>
      </span>

      <span v-if="!emptyTrack">
        <AudioVolumePopover
          @volume="changeVolume"
        >
          <IconButton
            :tooltip="tooltip.volume"
          >
            <SpeakerIcon />
          </IconButton>
        </AudioVolumePopover>
      </span>

    </div>
    <audio :src="currentTrack.filename" preload="auto"></audio>
  </footer>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import PlayNextIcon from 'components/icons/PlayNextIcon.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import LoopIcon from 'components/icons/LoopIcon.vue';
import HeartIcon from 'components/icons/HeartIcon.vue';
import SpeakerIcon from 'components/icons/SpeakerIcon.vue';
import AudioVolumePopover from 'components/AudioVolume/AudioVolumePopover.vue';
import PlayPreviousIcon from 'components/icons/PlayPreviousIcon.vue';
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import gql from 'graphql-tag';
import { mapState } from 'vuex';
import TrackToPlaylistPopover from '../trackList/TrackToPlaylistPopover/TrackToPlaylistPopover.vue';

const MOBILE_WIDTH = 767;

export default {
  components: {
    IconButton,
    PlayIcon,
    PauseIcon,
    PlayNextIcon,
    PlayPreviousIcon,
    HeartIcon,
    LoopIcon,
    PlusIcon,
    SpeakerIcon,
    TrackToPlaylistPopover,
    AddToFavouriteButton,
    AudioVolumePopover
  },
  data: () => ({
    percentage: Number,
    audio: undefined,
    currentTime: null,
    fixedTime: null,
    toggleLoop: false,
    volumeToggle: false,
    tooltip: {
      add: {
        content: 'Добавить в плейлист'
      },
      loop: {
        content: 'Повтор'
      },
      like: {
        content: 'Мне нравится'
      },
      volume: {
        content: 'Громкость'
      }
    }
  }),
  methods: {
    changeVolume(value) {
      this.audio.volume = value;
    },
    onPressFavourite() {
      this.$refs.addToFavouriteButton.$el.dispatchEvent(new Event('click'));
    },
    seek(e) {
			if (e.target.tagName === 'div') {
				return;
			}
			const el = e.target.getBoundingClientRect();
      let barWidth = document.getElementsByClassName('progress__bar')[0].offsetWidth;
			const seekPos = (e.clientX - el.left) / barWidth;
			this.audio.currentTime = parseInt(Math.floor(this.currentTrack.length) * seekPos);
		},
    startPause(){
      if(!this.emptyTrack){
        if(this.playing === true){
          this.audio.pause();
          this.$store.commit('player/pausePlaying');
        }else{
          this.audio.play();
          this.$store.commit('player/startPlaying');
        }
      }
    },
    switchTrack(pos){
      if(!this.emptyTrack){
        let trackId = null;
        let currentIndex = this.currentPlaylist.indexOf(this.currentTrack.id);
        if(pos === 'next'){
          if(this.currentPlaylist.length === currentIndex + 1){
            trackId = this.currentPlaylist[0];
          }else{
            trackId = this.currentPlaylist[currentIndex + 1];
          }
        }else{
          trackId = this.currentPlaylist[currentIndex - 1];
        };
        this.$apollo.provider.defaultClient.mutate({
          variables: {
            id: this.currentTrack.id,
            listening: this.percentage
          },
          mutation: gql`mutation($id: Int!, $listening: Int!){
            listeningTrack(id: $id, listening: $listening){
              trackName
            }
          }`
        })
        .catch(error => {
          console.log(error);
        });
        this.getTrack(trackId);
      }
    },
    getTrack(id){
      this.$store.commit('player/pausePlaying');
      this.$apollo.provider.defaultClient.query({
        variables: {
          id: id
        },
        query: gql`query tracks {
          track(id: $id) {
            id
            filename
            singer
            trackName
            length
            userFavourite
            favouritesCount
            cover(
                sizes: [size_32x32, size_48x48, size_104x104, size_120x120, size_150x150]
            ) {
                size
                url
            }
          }
        }`
      }).then(response => {
        let type = this.$store.getters['player/getCurrentType'].type;
        let data = {
          'type': type,
          'id': response.data.track.id
        };
        this.$store.commit('player/changeCurrentType', data);
        this.$store.commit('player/pickTrack', response.data.track);
      })
    },
    update(e) {
      if(this.audio.duration){
        this.percentage =  Math.floor(this.audio.played.end(0) / this.audio.duration * 100);
      };
			this.currentTime = parseInt(this.audio.currentTime);
	    let hhmmss = new Date(this.currentTime * 1000).toISOString().substr(11, 8);
    	this.fixedTime =  hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
      let currentIndex = this.currentPlaylist.indexOf(this.currentTrack.id);
      if(this.audio.ended){
        if(this.toggleLoop){
          this.audio.currentTime = 0;
          this.audio.play();
        }else if(this.currentPlaylist.length === currentIndex + 1){
          let trackId = this.currentPlaylist[0];
          this.getTrack(trackId);
        }else{
          this.switchTrack('next');
        }
      }
		}
  },
  watch: {
    async playing(value) {
      await (this.currentTrack.path !== null);
			if (value) {
        return this.audio.play()
      } else {
        return this.audio.pause();
      }
		},
  },
  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },
    percentComplete() {
		  return this.currentTime / Math.floor(this.currentTrack.length) * 100 + '%';
		},
    currentLength() {
      let countdown = (Math.floor(this.currentTrack.length) - this.currentTime);
	    let hhmmss = new Date(countdown * 1000).toISOString().substr(11, 8);
    	return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
    },
    ...mapState('player', {
      playing: state => state.isPlaying
    }),
    ...mapState('player', {
      currentTrack: state => state.currentTrack
    }),
    ...mapState('player', {
      currentPlaylist: state => state.currentPlaylist
    }),
    emptyTrack() {
      if(Object.keys(this.currentTrack).length > 0){
        return false;
      }else{
        return true;
      }
    }
  },
  mounted: function(){
    this.audio = this.$el.querySelectorAll('audio')[0];
		this.audio.addEventListener('timeupdate', this.update);
		// this.audio.addEventListener('loadeddata', this.load);
  }
};
</script>


<style
  scoped
  lang="scss"
  src="../../../sass/app.scss"
/>
