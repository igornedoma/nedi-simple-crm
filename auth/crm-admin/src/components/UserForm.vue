<template>
    <form>
        <label for="inputEmail" class="visually-hidden">Email address</label>
        <input v-model="credentials.email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required :disabled="formEdit">
        <br>
        <label for="inputName" class="visually-hidden">Name</label>
        <input v-model="credentials.name" type="text" id="inputName" class="form-control" placeholder="Name" required>
        <br>
        <label for="inputPassword" v-if="formEdit">Password - leave blank if you don't want to change it.</label>
        <label for="inputPassword" class="visually-hidden" v-else>Password</label>
        <input v-model="credentials.pass" type="password" id="inputPassword" class="form-control" placeholder="Password" :required="!formEdit">
        <br>
        <select v-model="credentials.role" class="form-select" aria-label="Default select example" required>
            <option value="" disabled>Select role</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <br>
        <button type="submit" class="btn btn-primary" v-on:click="register">Submit</button>

    </form>
</template>
<script>

    import axios from "axios";

    export default {
        name: 'UserForm',
        components: {

        },
        props: ['userData'],
        data () {
            return {
                token: localStorage.getItem('authTokenSimpleCrm'),
                credentials: {
                    email: '',
                    name: '',
                    pass: '',
                    role: ''
                },
                formEdit: false
            }
        },
        methods:{
            register () {
                let url;
                if (this.formEdit) {
                    url = '/auth/api/user-edit';
                }
                else {
                    url = '/auth/api/registration';
                }
                let data = new FormData;
                let token = localStorage.getItem('authTokenSimpleCrm');

                data.append('email', this.credentials.email);
                data.append('name', this.credentials.name);
                if (this.credentials.pass != '') {
                    data.append('password', this.credentials.pass);
                }
                data.append('role', this.credentials.role);
                axios.post(url,data,{
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                }).then(res => {
                    this.$toast.open({
                        message: res.data.message,
                        type: 'success',
                        position: 'top-right'
                        // all of other options may go here
                    });
                    this.$router.push({name: 'Home'})
                }).catch(e => {
                    console.error(e)
                    this.$toast.open({
                        message: e.response.data.message,
                        type: 'error',
                        position: 'top-right'
                        // all of other options may go here
                    });
                })
            },
            console () {
                console.log(this.credentials)
            }
        },
        mounted() {
            if (this.$route.name == 'EditUser') {
                this.formEdit = true;
                axios.get('/auth/api/get-user/' + this.$route.params.id, {
                    headers: {
                        Authorization: 'Bearer ' + this.token
                    }
                }).then(res => {
                    this.credentials = res.data.user;
                    console.log(this.credentials)
                })

            }

        }
    }
</script>