import "@sintexph/vue-lib"
Vue.mixin(httpAlert);
Vue.component('home-page', require('./components/home/index.vue').default);
new Vue({
    el: '#doc-app'
});
