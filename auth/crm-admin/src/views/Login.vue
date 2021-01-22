<template>
<main class="form-signin">
    <form>
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        <label for="inputEmail" class="visually-hidden">Email address</label>
        <input v-model="credentials.email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="visually-hidden">Password</label>
        <input v-model="credentials.pass" type="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <button class="w-100 btn btn-lg btn-primary" type="submit" v-on:click="this.login">Sign in</button>
    </form>
</main>
</template>
<script>
    import axios from 'axios'

    export default {
        name: 'Login',
        components: {
            axios
        },data () {
            return {
                credentials: {
                    email: '',
                    pass: ''
                }
            }
        },
        methods: {
            login() {
                let data = new FormData;
                data.append('email', this.credentials.email)
                data.append('password', this.credentials.pass)
                axios.post('/auth/api/login',data).then(res => {
                    localStorage.setItem('authTokenSimpleCrm', res.data.token)
                    this.$router.push({name: 'Home'})
                }).catch(e => {
                    console.error(e)
                })
                localStorage.setItem('authTokenSimpleCrm', 'token')
            }
        },
        mounted() {
            console.log(this.$route.name)
        }
    }
</script>
<style>
    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }
    .form-signin .checkbox {
        font-weight: 400;
    }
    .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }
    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>