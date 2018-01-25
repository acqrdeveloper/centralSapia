import Vue from 'vue';
import VueRouter from 'vue-router';

import Report from './components/Report.vue';

Vue.use(VueRouter);

export const ROUTER = new VueRouter({
    mode: 'history',
    routes: [
        // REPORT
        {
            path: '/report',
            component: Report,
            name: 'report'
        }
    ]
});


