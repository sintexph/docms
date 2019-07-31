import "@sintexph/vue-lib"
Vue.mixin(httpAlert);

Vue.component('trashed-documents', require('./components/trashed/list.vue').default);

new Vue({
    el: '#app-trashed'
});
