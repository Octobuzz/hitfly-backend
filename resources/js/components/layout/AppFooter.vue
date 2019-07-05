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

      <div class="progress">
        <div class="progress__bar progress__text">
          <div class="progress__time">0:09</div>
          <div class="progress__time" v-if="currentTrack.duration">{{ currentTrack.duration }}</div>
          <div class="progress__time" v-else>--:--</div>
        </div>
        <div class="progress__bar progress__progress">
          <div class="progress__line" style="width: 28.25119%;">
            <div class="progress__head"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer__right">FOOTER</div>
    <audio :src="currentTrack.filename" preload="auto"></audio>
  </footer>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import PlayNextIcon from 'components/icons/PlayNextIcon.vue';
import PlayPreviousIcon from 'components/icons/PlayPreviousIcon.vue';
import gql from 'graphql-tag';
import { mapState } from 'vuex';

export default {
  components: {
    IconButton,
    PlayIcon,
    PauseIcon,
    PlayNextIcon,
    PlayPreviousIcon
  },
  data: () => ({
    audio: undefined,
  }),
  methods: {
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
          trackId = this.currentPlaylist[currentIndex + 1];
        }else{
          trackId = this.currentPlaylist[currentIndex - 1];
        };
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
            cover(
                sizes: [size_32x32, size_48x48, size_104x104, size_120x120, size_150x150]
            ) {
                size
                url
            }
          }
        }`
      }).then(response => {
        this.$store.commit('player/pickTrack', response.data.track);
      })
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
		// this.audio.addEventListener('timeupdate', this.update);
		// this.audio.addEventListener('loadeddata', this.load);
		// this.audio.addEventListener('pause', () => { this.$store.getters.isPlaying = false; });
		// this.audio.addEventListener('play', () => { this.$store.getters.isPlaying = true; });
  }
};
</script>


<style
  scoped
  lang="scss"
  src="../../../sass/app.scss"
/>
