import Vue from 'vue';
import Router from 'vue-router';
import LoginView from '../views/LoginView.vue';
import RegisterView from '../views/RegisterView.vue';
import DashboardView from '../views/DashboardView.vue';
import AdminPanelView from '../views/AdminPanelView.vue';

Vue.use(Router);

const routes = [
  {
    path: '/',
    name: 'Login',
    component: LoginView
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView
  },
  {
    path: '/admin',
    name: 'AdminPanel',
    component: AdminPanelView
  }
];

const router = new Router({
  mode: 'history',
  routes
});

export default router;