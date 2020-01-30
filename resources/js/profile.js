import "@sintexph/vue-lib"
Vue.mixin(toastHelper);
Vue.mixin(httpAlert);

import User from './src_classes/User';
window.User = User;

Vue.component('notification-settings', require('./components/profile/notifications.vue').default); 


 new Vue({
    el: '#app-profile'
});

 