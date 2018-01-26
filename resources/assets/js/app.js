import Vue from "vue";
import {ROUTER} from "./router";
import Axios from "axios/index";
import $ from "jquery";

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

new Vue({
    router: ROUTER,
    methods: {
        //Funciones Globales
        sideNavTooggle() {
            event.preventDefault();
            $("body").toggleClass("sidenav-toggled");
            $(".navbar-sidenav .nav-link-collapse").addClass("collapsed");
            $(".navbar-sidenav .sidenav-second-level, .navbar-sidenav .sidenav-third-level").removeClass("show");
        },
        scrollUp() {
            let $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: ($($anchor.attr('href')).offset().top)
            }, 1000, 'easeInOutExpo');
            event.preventDefault();
        }
    }
}).$mount('#app');