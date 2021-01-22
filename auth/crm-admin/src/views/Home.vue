<template>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <br>
    <h2>Users list</h2>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
        <tr>
          <th>#</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>1,001</td>
          <td>Lorem</td>
          <td>ipsum</td>
          <td>dolor</td>
          <td>sit</td>
        </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'
import axios from 'axios'

export default {
  name: 'Home',
  components: {
    HelloWorld,
    axios
  },
  mounted() {
    axios.get('/auth/api/user-info').then(res => {
      console.log(res)
    }).catch(e => {
      console.error(e)
    })
  }
}
</script>
