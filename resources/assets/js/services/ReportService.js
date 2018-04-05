import Vue from 'vue';
import Axios from 'axios';
import * as Vuex from 'vuex';

Vue.use(Vuex);

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Axios.defaults.headers.common['X-Api-Token'] = JSON.parse(window.localStorage.getItem('data_auth')).api_token;

const env = {
  API: 'http://web.central.sapia.pe/api',
};

const REPORT_SERVICE = new Vuex.Store({
  actions: {
    reportJson({commit}, {self}) {
      Axios.get(env.API + '/do-report', {params: self.params}).
      then((response) => {
        console.log(response);
        if (response.data.load) {
          self.loading = false;
          self.dataReport = response.data.data;
        }
      }).
      catch((error) => {
        if (!error.response.data.load) {
          switch (error.response.status) {
            case 412:// Exception Laravel
              console.error(error.response.data);
              break;
            default:// Request Laravel 401,422
              console.error(error.response.data);
              break;
          }
        }
      });
    },
    list({commit}, {self}) {
      Axios.get(env.API + 'select-report', {params: self.params}).
      then((response) => {
        if (response.data.load) {
          self.loading = false;
          self.dataReport = response.data.data;
        }
      }).
      catch((error) => {
        if (!error.response.data.load) {
          switch (error.response.status) {
            case 412:// Exception Laravel
              console.error(error.response.data);
              break;
            default:// Request Laravel 401,422
              console.error(error.response.data);
              break;
          }
        }
      });
    },
    usersToJson({commit}, {self}) {
      Axios.get(env.API + '/get-users', {params: self.params}).
      then((response) => {
        if (response.data.load) {
          self.loading = false;
          self.dataUsers = response.data.data;
        }
      }).
      catch((error) => {
        if (!error.response.data.load) {
          switch (error.response.status) {
            case 412:// Exception Laravel
              console.error(error.response.data);
              break;
            default:// Request Laravel 401,422
              console.error(error.response.data);
              break;
          }
        }
      });
    },
  },
});
export default REPORT_SERVICE;
