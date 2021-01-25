import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import axios from "axios";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/Login.vue')
    }
  },
  {
    path: '/add-user',
    name: 'AddUser',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/AddUser.vue')
    }
  },
  {
    path: '/import-users',
    name: 'ImportUsers',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/ImportUsers.vue')
    }
  },
  {
    path: '/edit-user/:id',
    name: 'EditUser',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/EditUser.vue')
    }
  }
]

const router = new VueRouter({
  routes
})

async function isAuth () {

  let token = localStorage.getItem('authTokenSimpleCrm');
  let y = await axios.get('/auth/api/user-info', {
    headers: {
      Authorization: 'Bearer ' + token
    }
  }).then( res => {
    let x = res.data
    if (x.status === 200 && x.user.role === 'admin') {
      return true;
    }
    else {
      return false;
    }
  }).catch(e => {
    console.error(e);
    return false;
  });

return y;

}

router.beforeEach(async (to, from, next) => {

  if (to.name === 'Login') {
    if (!to.query.needAuth) {
      if (await isAuth()) {
        next({name: 'Home'});
      }
      else {
        next();
      }
    }
    else {
      next();
    }

  } else {
    if (await isAuth()) {
      next();
    } else {
      next({name: 'Login', query: { needAuth: true }});
    }
  }

})
export default router
