import Vue from 'vue';
import VueRouter from 'vue-router';

import Report from './components/Report.vue';
import Example from './components/Example';
import UserProfile from './components/UserProfile';

Vue.use(VueRouter);

export const ROUTER = new VueRouter({
    mode: 'history',
    routes: [
        // REPORT
        {
            path: '/report',
            component: Report,
            name: 'report'
        },
        {
            path: '/home',
            component: Example,
            name: 'home'
        },
        {
            path: '/user-profile',
            component: UserProfile,
            name: 'user-profile'
        },
    ]
});


