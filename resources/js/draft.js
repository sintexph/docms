import "@sintexph/vue-lib"
Vue.mixin(httpAlert);


Vue.component('draft-list', require('./components/draft/list.vue').default);



new Vue({
    el: '#app-draft'
});
