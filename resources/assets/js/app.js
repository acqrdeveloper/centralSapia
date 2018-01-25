import Vue from "vue";
import {ROUTER} from "./router";
import Axios from "axios/index";

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

new Vue({
    router: ROUTER,
}).$mount('#app');