import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert);


Vue.component('draft-list', require('./components/draft/list.vue').default);



new Vue({
    el: '#app-draft'
});
