<template>
  <div class="toast d-flex align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
    <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>

</template>

<script>
export default {
  name: 'Toast',
  props: {
    msg: String
  },
  mounted() {
    this.$toast.open('you did it')
}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="less">

</style>
