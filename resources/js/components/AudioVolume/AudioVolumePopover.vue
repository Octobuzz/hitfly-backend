<template>
  <v-popover
    :popover-base-class="['volume-popover']"
    :placement="popoverPlacement"
  >
  <slot />
  <template #popover>
    <div
      class="volume"
      @click="seek"
    >
      <div class="volume__bar">
        <div class="volume__line" :style="{ height: percentVolume }">
          <div class="volume__head"></div>
        </div>
      </div>
    </div>
  </template>
  </v-popover>
</template>
<script>
  export default {
    props:{
      visible: {
        type: Boolean,
        default: false
      }
    },
    data: () => ({
      volume: 1,
      popoverPlacement: 'top'
    }),
    watch:{
      volume(value) {
        this.$emit('volume', value)
  		}
    },
    computed: {
      percentVolume() {
        return this.volume * 100 + '%';
      },
    },
    methods:{
      seek(e){
  			let el = e.target.getBoundingClientRect();
        let targetEl = document.getElementsByClassName('volume__bar')[0];
  			let seekPos = -((e.clientY - el.bottom) / targetEl.offsetHeight);
        console.log(seekPos);
        if(seekPos >= 1){
    			this.volume = 1;
        } else if(seekPos <= 0) {
    			this.volume = 0;
        }else{
  			  this.volume = seekPos;
        }
      }
    }
  }
</script>
<style lang="scss">
  .volume-popover {
    position: relative;
    z-index: 1100;


    &.open .volume {
      display: flex;
    }
  }

  .volume {
    background-color: #313131;
    width: 24px;
    height: 90px;
    border-radius: 3px;
    position: absolute;
    -ms-align-items: center;
    align-items: center;
    justify-content: center;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);

    &::after {
      position: absolute;
      content: '';
      display: block;
      bottom: -8px;
      border: 4px solid transparent;
      border-top: 4px solid #313131;
    }

    &__bar {
      width: 2px;
      height: 64px;
      position: relative;
      background-color: #e4e4e4;
      border-radius: 2px;
    }

    &__line {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      background-image: -webkit-gradient(linear, left bottom, left top, from(#4297FE), to(#DD61B9));
      background-image: -webkit-linear-gradient(top, #4297FE 0%, #DD61B9 100%);
      background-image: -o-linear-gradient(top, #4297FE 0%, #DD61B9 100%);
      background-image: linear-gradient(to top, #4297FE 0%, #DD61B9 100%);
    }

    &__head {
      position: relative;

      &::after {
        content: "";
        position: absolute;
        right: -3px;
        background: #fff;
        filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.5));
        height: 4px;
        border-radius: 2px;
        width: 8px;
      }
    }
  }
</style>
