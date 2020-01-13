import "@sintexph/vue-lib"

Vue.mixin(toastHelper);Vue.mixin(httpAlert);


Vue.component('manage-system', require('./components/system/list.vue').default);
Vue.component('create-system', require('./components/system/create.vue').default);
Vue.component('edit-system', require('./components/system/edit.vue').default);





new Vue({
    el: '#app-system'
});

