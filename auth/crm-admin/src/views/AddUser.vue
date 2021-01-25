<template>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="row justify-content-center">
      <div class="col-8">


    <br>
    <h2>User add</h2>
      <UserForm></UserForm>
      </div>
    </div>
  </main>
</template>
<script>

  import UserForm from "../components/UserForm";

  export default {
    name: 'AddUser',
    components: {
      UserForm
    },
    data () {
      return {

      }
    }

  }
</script>