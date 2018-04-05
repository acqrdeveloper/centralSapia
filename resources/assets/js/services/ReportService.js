import Vue from 'vue'
import Axios from 'axios'
import * as Vuex from 'vuex'

Vue.use(Vuex)

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
Axios.defaults.headers.common['X-Api-Token'] = JSON.parse(window.localStorage.getItem('data_auth')).api_token

const env = {
  API: 'http://web.central.sapia.pe/api',
}

const REPORT_SERVICE = new Vuex.Store({
  actions: {
    getReports ({commit}, {self}) {
      Axios.get(env.API + '/get-report', {params: self.params}).then((r) => {
        if (r.status === 200) {
          self.loading = false
          self.dataReport = r.data
        }
      }).catch((e) => {
        switch (e.response.status) {
          case 412:// Exception Laravel
            console.error(e.response.data)
            break
          default:// Request Laravel 401,422
            console.error(e.response.data)
            break
        }
      })
    },
    getUsers ({commit}, {self}) {
      Axios.get(env.API + '/get-users', {params: self.params}).then((r) => {
        if (r.status === 200) {
          self.loading = false
          self.dataUsers = r.data
        }
      }).catch((e) => {
        switch (e.response.status) {
          case 412:// Exception Laravel
            console.error(e.response.data)
            break
          default:// Request Laravel 401,422
            console.error(e.response.data)
            break
        }
      })
    },
  },
})
export default REPORT_SERVICE
