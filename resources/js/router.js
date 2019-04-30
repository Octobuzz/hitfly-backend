import Vue from 'vue';
import VueRouter from 'vue-router';
import * as main from './pages/main';
import * as profile from './pages/profile';
import UploadPage from './pages/upload/UploadPage.vue';

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
        path: '',
        component: profile.ProfilePage_obsolete
      }
    ]
  },
  {
    path: '/upload',
    component: UploadPage
  },
];

const router = new VueRouter({
  routes,
  mode: 'history'
});

router.customData = {
  navHistory: []
};

router.afterEach((to) => {
  const { navHistory } = router.customData;

  navHistory.unshift(to);

  if (navHistory > 100) {
    navHistory.pop();
  }
});

Vue.use(VueRouter);

export default router;
