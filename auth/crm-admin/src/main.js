import Vue from 'vue'
import App from './App.vue'
import router from './router'
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import SmartTable from 'vuejs-smart-table'

Vue.use(SmartTable)

Vue.config.productionTip = false
Vue.use(VueToast);
new Vue({
  router,
  render: function (h) { return h(App) }
}).$mount('#app')
