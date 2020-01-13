import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert);

Vue.component('manage-section', require('./components/section/list.vue').default);
Vue.component('create-section', require('./components/section/create.vue').default);
Vue.component('edit-section', require('./components/section/edit.vue').default);





new Vue({
    el: '#app-section'
});
