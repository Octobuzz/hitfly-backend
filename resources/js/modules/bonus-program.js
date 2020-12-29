import levelNoviceImgSrc from 'images/level-novice.svg';
import levelFanImgSrc from 'images/level-fan.svg';
import levelConnoisseurImgSrc from 'images/level-connoisseur.svg';
import levelMusicLoverImgSrc from 'images/level-music-lover.svg';

// eslint-disable-next-line import/prefer-default-export
export const bonusProgramLvl = {
  LEVEL_NOVICE: {
    slug: 'LEVEL_NOVICE',
    title: 'Новичок',
    image: levelNoviceImgSrc,
    points: 0,
    listenedTracks: 0,
    nextLevelSlug: 'LEVEL_AMATEUR'
  },
  LEVEL_AMATEUR: {
    slug: 'LEVEL_AMATEUR',
    title: 'Любитель',
    image: levelFanImgSrc,
    points: 400,
    listenedTracks: 0,
    nextLevelSlug: 'LEVEL_CONNOISSEUR_OF_THE_GENRE',
  },
  LEVEL_CONNOISSEUR_OF_THE_GENRE: {
    slug: 'LEVEL_CONNOISSEUR_OF_THE_GENRE',
    title: 'Знаток жанра',
    image: levelConnoisseurImgSrc,
    points: 3000,
    listenedTracks: 2500,
    levelListenedTracksDesc: 'Знатоком жанра слушай больше песен в своем любимом жанре',
    nextLevelSlug: 'LEVEL_SUPER_MUSIC_LOVER',
  },
  LEVEL_SUPER_MUSIC_LOVER: {
    slug: 'LEVEL_SUPER_MUSIC_LOVER',
    title: 'Супер меломан',
    image: levelMusicLoverImgSrc,
    points: 5000,
    listenedTracks: 10000,
    levelListenedTracksDesc: 'Супер меломаном слушай больше песен в своих любимых жанрах',
    nextLevelSlug: null
  }
};
