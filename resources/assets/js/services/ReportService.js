import Vue from 'vue';
import Axios from 'axios';
import * as Vuex from "vuex";

Vue.use(Vuex);

const REPORT_SERVICE = new Vuex.Store({
    state: {},
    mutations: {},
    actions: {
        list({commit}, {self}) {
            Axios.get("reportToJson", {params: self.params})
                .then((response) => {
                    if (response.data.load) {
                        self.loading = false;
                        self.dataReport = response.data.data;
                    }
                })
                .catch((error) => {
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
    }
});
export default REPORT_SERVICE;
