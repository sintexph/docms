import "@sintexph/vue-lib"

Vue.mixin(toastHelper);
Vue.mixin(httpAlert);


 import {
     ContentTitle
 }
 from './src_classes/ContentTitle';
 window.ContentTitle = ContentTitle;
 

Vue.component('manage-content-title', require('./components/content_title/list.vue').default);
Vue.component('create-content-title', require('./components/content_title/create.vue').default);
Vue.component('edit-content-title', require('./components/content_title/edit.vue').default);
Vue.component('content-title-form', require('./components/content_title/form.vue').default);

 




new Vue({
    el: '#app-content-title'
});

