import "@sintexph/vue-lib"

Vue.mixin(toastHelper);Vue.mixin(httpAlert);


 import {
     SystemClass
 }
 from './src_classes/SystemClass';
 window.SystemClass = SystemClass;
 

Vue.component('manage-system', require('./components/system/list.vue').default);
Vue.component('create-system', require('./components/system/create.vue').default);
Vue.component('edit-system', require('./components/system/edit.vue').default);
Vue.component('system-form', require('./components/system/form.vue').default);

 




new Vue({
    el: '#app-system'
});

