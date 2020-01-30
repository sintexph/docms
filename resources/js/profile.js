import "@sintexph/vue-lib"
Vue.mixin(toastHelper);
Vue.mixin(httpAlert);

import User from './src_classes/User';
window.User = User;

Vue.component('notification-form', require('./components/account/notification-form.vue').default);
Vue.component('notification-settings', require('./components/profile/notifications.vue').default); 


 new Vue({
    el: '#app-profile'
});

 