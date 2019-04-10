import Vue from 'vue';
import VueRouter from 'vue-router';
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
        path: '',
        component: profile.ProfilePage_obsolete
      }
    ]
  }
];

Vue.use(VueRouter);

export default new VueRouter({
  routes,
  mode: 'history'
});
