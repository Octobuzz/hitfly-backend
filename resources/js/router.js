import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import * as main from './pages/main';
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

  /profile/review/:id
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
        component: profile.Tracks
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
        component: profile.MyReviews
      },
      {
        path: 'review/:reviewId',
        component: profile.Review
      },
      {
        path: 'album/:albumId',
        component: profile.Album
      },
      {
        path: 'playlist/:playlistId',
        component: profile.Playlist
      },
      {
        path: 'set/:setId',
        component: profile.Set
      },
      {
        path: 'bonus-program',
        component: profile.BonusProgram
      },
      {
        path: '',
        redirect: '/profile/my-music'
      }
    ]
  },
  // {
  //   path: 'user/:userId',
  //   component: profile.OtherUserProfileLayout,
  //   children: [
  //     {
  //       // TODO: redirect from star profile
  //       path: 'music',
  //       component: ''
  //     },
  //     {
  //       path: 'reviews',
  //       component: ''
  //     }
  //   ]
  // },
  {
    path: '/upload',
    component: UploadPage
  },
  // {
  //   path: '/about',
  //   component: AboutPage
  // }
];

const router = new VueRouter({
  routes,
  mode: 'history'
});

router.beforeEach((to, from, next) => {
  store.commit('history/push', to);
  next();
});

Vue.use(VueRouter);

export default router;
