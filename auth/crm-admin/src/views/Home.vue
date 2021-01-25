<template>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <br>
    <h2>Users list</h2>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody v-if="userList">
        <tr v-for="item in userList">
          <td>{{item.id}}</td>
          <td>{{item.name}}</td>
          <td>{{item.email}}</td>
          <td>{{item.role}}</td>
          <td>
            <button type="button" class="btn btn-primary btn-sm" style="margin-right: 10px" v-on:click="$router.push({name: 'EditUser', params: {id: item.id}})">Edit</button>
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" v-on:click="deleteId = item.id">Delete</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Are you sure?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure? The user will be deleted permanently!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" v-on:click="deleteId = null">Close</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" v-on:click="deleteUser(deleteId)">Delete User</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
// @ is an alias to /src
import axios from 'axios';

export default {
  name: 'Home',
  components: {
    axios
  },
  data () {
    return {
      userList: null,
      token: localStorage.getItem('authTokenSimpleCrm'),
      deleteId: null,

    }
  },
  methods: {
    deleteUser (id) {
      axios.delete('/auth/api/user-delete/' + id, {
        headers: {
          Authorization: 'Bearer ' + this.token
        }
      }).then(res => {

        this.fetchList();
        this.$toast.open({
          message: res.data.message,
          type: 'success',
          position: 'top-right'
          // all of other options may go here
        });
      })
    },
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
