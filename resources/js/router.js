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
import Page404 from './pages/Page404.vue';

const PERFORMER = 'performer';
const CRITIC = 'critic';
const PROF_CRITIC = 'prof_critic';
const STAR = 'star';

const hasRoles = (roles) => {
  const { hasRole } = store.getters['profile/roles'];

  return roles.some(role => hasRole(role));
};

const isAuthenticated = () => store.getters.isAuthenticated;

// beforeRouteEnterFactory arg:
//
// {
//   shouldBeAuthenticated: {
//     redirect
//   },
//   shouldHaveRoles: {
//     rolesToHave,
//     redirect
//   },
//   shouldNotHaveRoles: {
//     rolesNotToHave,
//     redirect
//   }
// }

const beforeRouteEnterFactory = ({
  shouldBeAuthenticated,
  shouldHaveRoles,
  shouldNotHaveRoles
}) => ({
  beforeRouteEnter(to, from, next) {
    if (shouldBeAuthenticated && !isAuthenticated()) {
      next(shouldBeAuthenticated.redirect);

      return;
    }
    if (shouldHaveRoles && !hasRoles(shouldHaveRoles.rolesToHave)) {
      next(shouldHaveRoles.redirect);

      return;
    }
    if (shouldNotHaveRoles && hasRoles(shouldNotHaveRoles.rolesNotToHave)) {
      next(shouldNotHaveRoles.redirect);

      return;
    }
    next();
  }
});

const routes = [
  {
    path: '/profile',
    component: profile.MyProfileLayout,
    ...beforeRouteEnterFactory({
      shouldBeAuthenticated: {
        redirect: '/'
      },
    }),
    children: [
      {
        path: 'edit',
        component: profile.EditUser
      },
      {
        path: 'create-group',
        component: profile.CreateGroup,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'edit-group/:editGroupId',
        component: profile.UpdateGroup,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'my-music',
        component: profile.MyMusic,
        name: 'profile-my-music',
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'my-music/tracks',
        component: profile.UserTrackList,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'my-music/albums',
        component: profile.AlbumTableContainer,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'my-music/playlists',
        component: profile.CollectionTableContainer,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'favourite',
        component: profile.Favourite
      },
      {
        path: 'favourite/tracks',
        component: profile.FavouriteTrackList
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
        path: 'my-reviews',
        component: profile.UniversalReviews,
        name: 'profile-my-reviews',
        ...beforeRouteEnterFactory({
          shouldHaveRoles: {
            rolesToHave: [CRITIC, PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'album/:albumId',
        component: profile.AlbumTrackList,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'playlist/:playlistId',
        component: profile.CollectionTrackList,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
      },
      {
        path: 'set/:setId',
        component: profile.CollectionTrackList,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            redirect: '/profile/my-reviews'
          }
        })
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
      if (!isAuthenticated()) {
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
    path: '*',
    component: Page404
  },
  {
    path: '/',
    component: main.MainPageLayout,
    children: [
      {
        path: '/',
        component: main.MainPageDefault,
        name: 'main'
      },
      {
        path: 'recommended',
        component: main.CollectionTableContainer,
      },
      {
        path: 'super-melomaniac',
        component: main.CollectionTableContainer,
      },
      {
        path: 'top50',
        component: main.MainPageTrackList,
      },
      {
        path: 'playlist/:playlistId',
        component: main.CollectionTrackList
      },
      {
        path: 'news/:newsId',
        component: main.MainPageNewsDetailed,
        props: true
      },
      {
        path: '',
        redirect: { name: 'main' }
      }
    ]
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
