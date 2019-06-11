import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import * as main from './pages/main';
import * as profile from './pages/profile';
import UploadPage from './pages/upload/UploadPage.vue';
import AboutPage from './pages/AboutPage.vue';

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
        component: profile.MyMusic
      },
      {
        path: 'favourite',
        component: profile.Favourite
      },
      {
        path: 'reviews',
        component: profile.MyReviews
      },
      {
        path: 'bonus-program',
        component: profile.BonusProgram
      },
      {
        path: '',
        redirect: '/profile/my-music'
        // component: profile.ProfilePage_obsolete
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
