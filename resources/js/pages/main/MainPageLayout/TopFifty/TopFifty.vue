<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h4>Топ 50</h4>
      <p>Рейтинг лучших музыкантов</p>
      <button>Все</button>
    </div>
    <div class="asideBlock__body">
      <TrackListEntry
        v-for="(trackId, index) in trackIdList"
        :key="trackId"
        :index="index + 1"
        :track-id="trackId"
        :show-album-section="showAlbumSection"
        :fake-fav-button="fakeFavButton"
        :show-remove-button="showRemoveButton"
        @remove-track="onTrackRemove"
        @press-favourite="onFavouritePress"
        @play-track="playTrack"
      />
    </div>
  </div>
</template>
<script>
  export default {
    data: () => ({

    }),
  }
</script>
<style
  scoped
  lang="scss"
  src="./TopFifty.scss"
/>
