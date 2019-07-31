import "@sintexph/vue-lib"
Vue.mixin(httpAlert);


Vue.component('manage-category', require('./components/category/list.vue').default);
Vue.component('create-category', require('./components/category/create.vue').default);
Vue.component('edit-category', require('./components/category/edit.vue').default);





new Vue({
    el: '#app-category'
});
