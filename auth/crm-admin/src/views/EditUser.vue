<template>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="row justify-content-center">
            <div class="col-8">


                <br>
                <h2>User edit</h2>
                <UserForm :userData="data"></UserForm>
            </div>
        </div>
    </main>
</template>
<script>

    import UserForm from "../components/UserForm";
    import axios from 'axios'

    export default {
        name: 'EditUser',
        components: {
            UserForm
        },
        data () {
            return {
                token: localStorage.getItem('authTokenSimpleCrm'),
                data: null
            }
        },
        beforeMount() {
        }

    }
</script>