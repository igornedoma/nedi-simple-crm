<template>
    <div>
    <div class="table-responsive">
        <label>Filter by Name, Email or Role:</label>
        <input class="form-control" v-model="filters.name.value"/>
        <br>
        <v-table class="table table-striped table-sm"
                :data="users"
                :filters="filters"
                 :currentPage.sync="currentPage"
                 :pageSize="20"
                 @totalPagesChanged="totalPages = $event"
        >
            <thead slot="head">
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
            </thead>
            <tbody slot="body" slot-scope="{displayData}">
            <tr v-for="row in displayData" :key="row.id">

                <td>{{ row.id }}</td>
                <td>{{ row.name }}</td>
                <td>{{ row.email }}</td>
                <td>{{ row.role }}</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" style="margin-right: 10px" v-on:click="$router.push({name: 'EditUser', params: {id: row.id}})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" v-on:click="deleteId = row.id">Delete</button>
                </td>

            </tr>
            </tbody>
        </v-table>
        <smart-pagination
                :currentPage.sync="currentPage"
                :totalPages="totalPages"
        />
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
    </div>
</template>
<script>
    import axios from "axios";

    export default {
        name: 'UserTable',
        props: ['users'],
        data: () => ({
            filters: {
                name: { value: '', keys: ['name', 'email', 'role'] }
            },
            token: localStorage.getItem('authTokenSimpleCrm'),
            deleteId: null,
            currentPage: 1,
            totalPages: 0
        }),
        mounted() {

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
                    this.$emit('reloadList')
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
        }
    }
</script>