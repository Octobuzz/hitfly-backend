import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import * as profile from './pages/profile';
import UploadPage from './pages/upload/UploadPage.vue';
import AboutPage from './pages/AboutPage.vue';

/*
  /profile/edit

  /profile/edit-group

  /profile/my-music

  /profile/my-music/tracks

  /profile/my-music/albums

  /profile/my-music/album/:id

  /profile/my-music/playlists

  /profile/my-music/playlist/:id

  /profile/favourite

  /profile/favourite/tracks

  /profile/favourite/albums

  /profile/favourite/album/:id

  /profile/favourite/playlists

  /profile/favourite/playlist/:id

  /profile/favourite/sets

  /profile/favourite/set/:id

  /profile/reviews

  /profile/reviews/:id

  /profile/my-reviews

  /profile/my-reviews/:id

  /user/:userId/music

  /user/:userId/music/tracks

  /user/:userId/music/albums

  /user/:userId/music/albums/:id

  /user/:userId/music/playlists

  /user/:userId/music/playlists/:id

  /user/:userId/reviews

  /user/:userId/reviews/:id

  /user/:userId/user-reviews

  /user/:userId/user-reviews/:id
*/

const routes = [
  {
    path: '/profile',
    component: profile.MyProfileLayout,
    children: [
      {
        path: 'edit',
        component: profile.EditUser
      },
      {
        path: 'create-group',
        component: profile.CreateGroup
      },
      {
        path: 'edit-group/:editGroupId',
        component: profile.UpdateGroup
      },
      {
        path: 'my-music',
        component: profile.MyMusic,
      },
      {
        path: 'my-music/tracks',
        component: profile.UserTrackList
      },
      {
        path: 'my-music/albums',
        component: profile.AlbumTableContainer
      },
      {
        path: 'my-music/playlists',
        component: profile.CollectionTableContainer
      },
      {
        path: 'favourite',
        component: profile.Favourite
      },
      {
        path: 'favourite/tracks',
        component: profile.Tracks
      },
      {
        path: 'favourite/albums',
        component: profile.AlbumTableContainer
      },
      {
        path: 'favourite/playlists',
        component: profile.CollectionTableContainer
      },
      {
        path: 'favourite/sets',
        component: profile.CollectionTableContainer
      },
      {
        path: 'reviews',
        component: profile.UniversalReviews
      },
      {
        path: 'reviews/:trackId',
        component: profile.UniversalReviews
      },
      {
        path: 'album/:albumId',
        component: profile.AlbumTrackList
      },
      {
        path: 'playlist/:playlistId',
        component: profile.CollectionTrackList
      },
      {
        path: 'set/:setId',
        component: profile.CollectionTrackList
      },
      {
        path: 'bonus-program',
        component: profile.BonusProgram
      },
      {
        path: 'notifications',
        component: profile.UserNotifications
      },
      {
        path: '',
        redirect: '/profile/my-music'
        // TODO: use name for redirect
      }
    ]
  },
  {
    path: '/user/:userId',
    component: profile.OtherUserProfileLayout,
    children: [
      {
        path: 'music',
        component: profile.OtherUserMusic
      },
      {
        path: 'music/tracks',
        component: profile.UserTrackList
      },
      {
        path: 'music/albums',
        component: profile.AlbumTableContainer
      },
      {
        path: 'music/playlists',
        component: profile.CollectionTableContainer
      },
      {
        path: 'reviews',
        component: profile.UniversalReviews
      },
      {
        path: 'user-reviews',
        component: profile.UniversalReviews
      },
      {
        path: 'reviews/:trackId',
        component: profile.UniversalReviews
      },
      {
        path: 'user-reviews/:trackId',
        component: profile.UniversalReviews
      },
      {
        path: 'album/:albumId',
        component: profile.AlbumTrackList
      },
      {
        path: 'playlist/:playlistId',
        component: profile.CollectionTrackList
      }
      // {
      //   path: '',
      //   redirect: '/user/:userId/music'
      //   // use name for redirect
      // },
    ]
  },
  {
    path: '/upload',
    component: UploadPage
  },
  {
    path: '/about',
    component: AboutPage
  }
];

const router = new VueRouter({
  routes,
  mode: 'history'
});

// TODO: consider using after hook
router.beforeEach((to, from, next) => {
  store.commit('history/push', to);
  next();
});

Vue.use(VueRouter);

export default router;
