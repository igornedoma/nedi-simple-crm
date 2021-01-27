<template>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <br>
    <h2>Users list</h2>
    <UserTable :users="userList" v-if="userList" @reloadList="fetchList"/>

  </main>
</template>

<script>
// @ is an alias to /src
import axios from 'axios';
import UserTable from "../components/UserTable";
export default {
  name: 'Home',
  components: {
    axios,
    UserTable
  },
  data () {
    return {
      userList: null,
      token: localStorage.getItem('authTokenSimpleCrm'),


    }
  },
  methods: {
    fetchList () {
      axios.get('/auth/api/user-list', {
        headers: {
          Authorization: 'Bearer ' + this.token
        }
      }).then(res => {
        this.userList = res.data.users;
      })
    }
  },
  beforeMount() {
    this.fetchList();
  },
}
</script>
