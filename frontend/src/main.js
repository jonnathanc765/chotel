import '@babel/polyfill'
import 'mutationobserver-shim'
import Vue from 'vue'
import './plugins/bootstrap-vue'
import App from './App.vue'
import axios from 'axios'
import Vuelidate from 'vuelidate'
import Vuex from 'vuex'

Vue.use(Vuex)
Vue.use(Vuelidate)

Vue.config.productionTip = false

Vue.prototype.$http = axios;

const store = new Vuex.Store({
  state: {
    reservations: []
  },
  mutations: {
    getReservations(state) {
    
      return axios.get(`http://127.0.0.1:8000/api/room/reservation`)
      .then(response => {
          
          state.reservations = response.data.reservations;

      })
      .catch(error => {
          this.$bvToast.toast('A error was ocurred!, try again', {
              title: 'Important Info',
              variant: 'danger',
              solid: true
          });
          console.log(error);
      });  

    },
    destroyReservation(state, reservationId) {
      return axios.post(`http://127.0.0.1:8000/api/room/reservation/${reservationId}`, {
        _method: 'DELETE'
      })
      .then(response => {
          console.log(response);

          let index = state.reservations.findIndex(reservation => {
            return reservation.id == reservationId
          })

          state.reservations.splice(index, 1)

      })
      .catch(error => {
          console.log(error);
      });  
    }
  },
});

new Vue({
  render: h => h(App),
  store
}).$mount('#app');