import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert); 
Vue.component('home-filter', require('./components/home/home_filter.vue').default);

new Vue({
    el: '#doc-home'
});
