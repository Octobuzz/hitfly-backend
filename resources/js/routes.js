import Main from './components/MainPage.vue'
import Profile from './components/ProfilePage.vue'
import Blog from './components/Blog.vue'
import Playlists from './components/Playlists.vue'
import About from './components/About.vue'

export const routes = [
  {
    path: '/main',
    component: MainPage,
  },
  {
    path: '/profile',
    component: ProfilePage,
  },
  {
    path: '/playlists',
    component: Playlists,
  },
  {
    path: '/blog',
    component: Blog,
  },
  {
    path: '/about',
    component: About,
  },
];
