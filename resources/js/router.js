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

const PERFORMER = 'performer';
const CRITIC = 'critic';
const PROF_CRITIC = 'prof_critic';
const STAR = 'star';

export const routeNames = {
  profile: {
    MY_MUSIC_ALBUM: '/profile/my-music/album/:albumId',
    MY_MUSIC_PLAYLIST: '/profile/my-music/playlist/:playlistId'
  }
};

const renderNotFoundPage = () => {
  store.commit('appColumns/set404', true);
};

const hasRoles = roles => (
  roles.some(role => (
    store.getters['profile/roles'](role)
  ))
);

const isAuthenticated = () => store.getters.isAuthenticated;

// beforeRouteEnterFactory arg:
//
// {
//   shouldBeAuthenticated: {
//     redirect
//     renderNotFound
//   },
//   shouldHaveRoles: {
//     rolesToHave,
//     redirect
//     renderNotFound
//   },
//   shouldNotHaveRoles: {
//     rolesNotToHave,
//     redirect
//     renderNotFound
//   }
// }

const beforeRouteEnterFactory = ({
  shouldBeAuthenticated,
  shouldHaveRoles,
  shouldNotHaveRoles
}) => ({
  beforeEnter(to, from, next) {
    if (shouldBeAuthenticated && !isAuthenticated()) {
      const { redirect, renderNotFound } = shouldBeAuthenticated;

      if (redirect) {
        next(redirect);
      }
      if (renderNotFound) {
        renderNotFoundPage();
      }
      return;
    }

    if (shouldHaveRoles && !hasRoles(shouldHaveRoles.rolesToHave)) {
      const { redirect, renderNotFound } = shouldHaveRoles;

      if (redirect) {
        next(shouldHaveRoles.redirect);
      }
      if (renderNotFound) {
        renderNotFoundPage();
      }
      return;
    }

    if (shouldNotHaveRoles && hasRoles(shouldNotHaveRoles.rolesNotToHave)) {
      const { redirect, renderNotFound } = shouldNotHaveRoles;

      if (redirect) {
        next(shouldNotHaveRoles.redirect);
      }
      if (renderNotFound) {
        renderNotFoundPage();
      }
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
        renderNotFound: true
      },
    }),
    meta: {
      title: 'Профиль'
    }
    ,
    children: [
      {
        path: 'edit',
        component: profile.EditUser,
        meta: {
          title: 'Редактировать профиль'
        }
      },
      {
        path: 'create/album',
        component: profile.CreateNewAlbum,
        meta: {
          title: 'Создать альбом'
        }
      },
      {
        path: 'edit/playlist/:collectionId',
        component: profile.EditCollection,
        meta: {
          title: 'Редактирование коллекции'
        }
      },
      {
        path: 'create-group',
        component: profile.CreateGroup,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Создать группу'
        }
      },
      {
        path: 'edit-group/:editGroupId',
        component: profile.UpdateGroup,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Редактирование группы'
        }
      },
      {
        path: 'edit/track/:editTrackId',
        component: profile.UpdateTrack,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Редактирование трека'
        }
      },
      {
        path: 'edit/album/:editAlbumId',
        component: profile.UpdateAlbum,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Редактирование альбома'
        }
      },
      {
        path: 'my-music',
        component: profile.MyMusic,
        name: 'profile-my-music',
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        })
        ,
        meta: {
          title: 'Моя музыка'
        }
      },
      {
        path: 'my-music/tracks',
        component: profile.UserTrackList,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Мои треки'
        }
      },
      {
        path: 'my-music/albums',
        component: profile.AlbumTableContainer,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        })
        ,
        meta: {
          title: 'Мои альбомы'
        }
      },
      {
        path: 'my-music/playlists',
        component: profile.CollectionTableContainer,
        ...beforeRouteEnterFactory({
          shouldNotHaveRoles: {
            rolesNotToHave: [PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Мои плейлисты'
        }
      },
      {
        path: 'my-music/album/:albumId',
        component: profile.AlbumTrackList,
        name: routeNames.profile.MY_MUSIC_ALBUM,
        meta: {
          title: 'Моя музыка'
        }
      },
      {
        path: 'my-music/playlist/:playlistId',
        component: profile.CollectionTrackList,
        name: routeNames.profile.MY_MUSIC_PLAYLIST,
        meta: {
          title: 'Моя музыка'
        }
      },
      {
        path: 'my-music/set/:setId',
        component: profile.CollectionTrackList,
        meta: {
          title: 'Моя музыка'
        }
      },
      {
        path: 'favourite',
        component: profile.Favourite,
        meta: {
          title: 'Избранное'
        }
      },
      {
        path: 'favourite/tracks',
        component: profile.FavouriteTrackList,
        meta: {
          title: 'Избранное треки'
        }
      },
      {
        path: 'favourite/albums',
        component: profile.AlbumTableContainer,
        meta: {
          title: 'Избранное альбомы'
        }
      },
      {
        path: 'favourite/playlists',
        component: profile.CollectionTableContainer,
        meta: {
          title: 'Избраное плейлисты'
        }
      },
      {
        path: 'favourite/sets',
        component: profile.CollectionTableContainer,
        meta: {
          title: 'Избранное коллекции'
        }
      },
      {
        path: 'favourite/album/:albumId',
        component: profile.AlbumTrackList,
        meta: {
          title: 'Избранное'
        }
      },
      {
        path: 'favourite/playlist/:playlistId',
        component: profile.CollectionTrackList,
        meta: {
          title: 'Избранное'
        }
      },
      {
        path: 'favourite/set/:setId',
        component: profile.CollectionTrackList,
        meta: {
          title: 'Избраннное'
        }
      },
      {
        path: 'reviews',
        component: profile.UniversalReviews,
        meta: {
          title: 'Отзывы'
        }
      },
      {
        path: 'my-reviews',
        component: profile.UniversalReviews,
        name: 'profile-my-reviews',
        ...beforeRouteEnterFactory({
          shouldHaveRoles: {
            rolesToHave: [CRITIC, PROF_CRITIC, STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Мои отзывы'
        }
      },
      {
        path: 'reviews/:trackId',
        component: profile.UniversalReviews,
        meta: {
          title: 'Отзывы на трек'
        }
      },
      {
        path: 'watched-users',
        component: profile.WatchedUsersContainer,
        meta: {
          title: 'Слежу'
        }
      },
      {
        path: 'review-requests',
        component: profile.ReviewRequestsContainer,
        ...beforeRouteEnterFactory({
          shouldHaveRoles: {
            rolesToHave: [STAR],
            renderNotFound: true
          }
        }),
        meta: {
          title: 'Запросы отзывов'
        }
      },
      {
        path: 'bonus-program',
        component: profile.BonusProgram,
        meta: {
          title: 'Бонусная программа'
        }
      },
      {
        path: 'notifications',
        component: profile.UserNotifications,
        meta: {
          title: 'Оповещения'
        }
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
        name: 'user-music',
        meta: {
          title: 'Моя мызыка'
        }
      },
      {
        path: 'music/tracks',
        component: profile.UserTrackList,
        meta: {
          title: 'Мои треки'
        }
      },
      {
        path: 'music/albums',
        component: profile.AlbumTableContainer,
        meta: {
          title: 'Мои альбомы'
        }
      },
      {
        path: 'music/playlists',
        component: profile.CollectionTableContainer,
        meta: {
          title: 'Мои плейлисты'
        }
      },
      {
        path: 'reviews',
        component: profile.UniversalReviews,
        meta: {
          title: 'Отзывы'
        }
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
    component: UploadPage,
    ...beforeRouteEnterFactory({
      shouldBeAuthenticated: {
        renderNotFound: true
      },
      shouldNotHaveRoles: {
        rolesNotToHave: [PROF_CRITIC, STAR],
        renderNotFound: true
      }
    }),
    meta: {
      title: 'Загрузить трек'
    }
  },
  {
    path: '/',
    component: main.MainPageLayout,
    children: [
      {
        path: '/',
        component: main.MainPageDefault,
        name: 'main',
        meta: {
          title: 'Myhitfly'
        }
      },
      {
        path: 'recommended',
        meta: {
          title: 'Рекомендации'
        },
        component: main.CollectionTableContainer,
      },
      {
        path: 'super-melomaniac',
        component: main.CollectionTableContainer,
        meta: {
          title: 'Супермеломан'
        },
      },
      {
        path: 'top50',
        component: main.MainPageTrackList,
        meta: {
          title: 'ТОП 50'
        },
      },
      {
        path: 'listening_now',
        component: main.MainPageTrackList,
        meta: {
          title: 'Слушают сейчас'
        },
      },
      {
        path: 'weekly_top',
        component: main.MainPageTrackList,
        meta: {
          title: 'Топ недели'
        },
      },
      {
        path: 'new_songs',
        component: main.MainPageTrackList,
        meta: {
          title: 'Новое'
        },

      },
      {
        path: 'genre/:genreId',
        component: main.MainPageGenreTrackList,
        meta: {
          title: 'Жанр'
        },
      },
      {
        path: 'playlist/:playlistId',
        component: profile.CollectionTrackList,
        meta: {
          title: 'Плейлист'
        },
      },
      {
        path: 'news/:newsId',
        component: main.MainPageNewsDetailed,
        props: true,
        meta: {
          title: 'Новости'
        },
      },
      {
        path: 'reviews',
        component: main.MainPageReviews,
        meta: {
          title: 'Отзывы'
        },

      },
      {
        path: 'bonus-program',
        component: profile.BonusProgram,
        meta: {
          title: 'Бонусная программа'
        },
      },
      {
        path: 'contacts',
        component: FaqPage,
        meta: {
          title: 'Контакты'
        },
      },
      {
        path: '',
        redirect: { name: 'main' }
      }
    ]
  },
  {
    path: '/about',
    component: AboutPage,
    meta: {
      title: 'О нас'
    },
  },
  {
    path: '*',
    name: 'asterisk',
    meta: {
      title: 'Стариница не найдена'
    }
  },
];

const router = new VueRouter({
  routes,
  mode: 'history'
});

router.beforeEach((to, from, next) => {
  // TODO: consider using after hook
  store.commit('history/push', to);
  document.title = to.meta.title;

  if (to.name === 'asterisk') {
    store.commit('appColumns/set404', true);
  } else {
    store.commit('appColumns/set404', false);
  }

  next();
});

router.afterEach(() => {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
});

Vue.use(VueRouter);

export default router;
