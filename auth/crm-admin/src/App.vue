<template>
  <div id="app">

    <header v-if="this.$route.name != 'Login'" class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Simple CRM</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">

            <a class="nav-link" v-on:click="logout">Sign out</a>

        </li>
      </ul>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav v-if="this.$route.name != 'Login'" id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
          <div class="position-sticky pt-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                  <span data-feather="home"></span>
                  <router-link to="/" class="nav-link" active-class="active">Home</router-link>
              </li>
              <li class="nav-item">
                <span data-feather="users"></span>
                <router-link to="/add-user" class="nav-link" active-class="active">Add user</router-link>
              </li>
              <li class="nav-item">
                <span data-feather="layers"></span>
                <router-link to="/import-users" class="nav-link" active-class="active">Import users</router-link>
              </li>
            </ul>
          </div>
        </nav>

        <router-view/>

      </div>
    </div>



  </div>

</template>

<script>
  import axios from 'axios'

  export default {
    name: 'App',
    components: {
      axios
    },data () {
      return {

      }
    },
    methods: {
      logout() {
        localStorage.removeItem('authTokenSimpleCrm')
        this.$router.push('/login?needAuth=true')
      }

    },
    mounted() {

    }
  }
</script>

<style lang="less">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
