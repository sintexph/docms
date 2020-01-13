import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert);


Vue.component('manage-account', require('./components/account/list.vue').default);
Vue.component('create-account', require('./components/account/create.vue').default);
Vue.component('edit-account', require('./components/account/edit.vue').default);





new Vue({
    el: '#app-account'
});
