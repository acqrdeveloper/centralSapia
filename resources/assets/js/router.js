import Vue       from 'vue'
import VueRouter from 'vue-router'
import Report    from './components/Report.vue'
import Home      from './components/Home'

Vue.use(VueRouter)

let router = new VueRouter({
  mode: 'history',
  routes: [
    // REPORT
    {path: '/report', component: Report, name: 'report'},
    {path: '/', component: Home, name: 'home'},
  ],
})

export default router


