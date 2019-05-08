import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import * as main from './pages/main';
import * as profile from './pages/profile';

const routes = [
  {
    path: '/',
    component: main.MainPage
  },
  {
    path: '/profile',
    component: profile.ProfilePage,
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
        path: 'update-group',
        component: profile.UpdateGroup
      },
      {
        path: 'my-music',
        component: profile.MyMusic
      },
      {
        path: '',
        redirect: '/profile/my-music'
        // component: profile.ProfilePage_obsolete
      }
    ]
  }
];

const router = new VueRouter({
  routes,
  mode: 'history'
});

router.customData = {
  navHistory: []
};

router.beforeEach((to, from, next) => {
  if (!store.getters['profile/customRedirect']
      && to.fullPath === '/profile/update-group') {
    router.customData.navHistory = [];
    store.commit('profile/flushEditGroupIdHistory');

    return next('/profile');
  }
  return next();
});

router.afterEach((to) => {
  store.commit('profile/setCustomRedirect', false);
  router.customData.navHistory.unshift(to);
});

Vue.use(VueRouter);

export default router;
