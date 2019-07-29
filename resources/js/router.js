import Vue from 'vue';
import VueRouter from 'vue-router';
import MY_ID_AND_ROLES from 'gql/query/MyIdAndRoles.graphql';
import store from './store';
import { cache } from './apolloProvider';
import * as profile from './pages/profile';
import UploadPage from './pages/upload/UploadPage.vue';
import * as main from './pages/main';
import AboutPage from './pages/AboutPage.vue';
import FaqPage from './pages/FaqPage.vue';

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
        name: 'profile-my-music'
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
      // {
      //   path: 'favourite/tracks',
      //   component: profile.FavouriteTrackList
      // },
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
        path: 'my-reviews',
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
        path: 'reviews/:trackId',
        component: profile.UniversalReviews
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
        redirect: { name: 'profile-my-music' }
      }
    ]
  },
  {
    path: '/user/:userId',
    component: profile.OtherUserProfileLayout,
    beforeEnter({ params: { userId } }, from, next) {
      const { isAuthenticated } = store.getters;

      if (!isAuthenticated) {
        next();

        return;
      }

      const { myProfile: { id: myId } } = cache.readQuery({
        query: MY_ID_AND_ROLES
      });

      if (+userId === myId) {
        next({ name: 'profile-my-music' });

        return;
      }
      next();
    },
    children: [
      {
        path: 'music',
        component: profile.OtherUserMusic,
        name: 'user-music'
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
        path: 'album/:albumId',
        component: profile.AlbumTrackList
      },
      {
        path: 'playlist/:playlistId',
        component: profile.CollectionTrackList
      },
      {
        path: 'reviews/:trackId',
        component: profile.UniversalReviews
      },
      {
        path: '',
        redirect: { name: 'user-music' }
      },
    ]
  },
  {
    path: '/upload',
    component: UploadPage
  },
  {
    path: '/home',
    component: main.MainPageLayout
  },
  {
    path: '/about',
    component: AboutPage
  },
  {
    path: '/faq',
    component: FaqPage
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
