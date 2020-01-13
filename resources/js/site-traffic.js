import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert);

Vue.component('site-traffic', require('./components/site-traffic/index.vue').default);

Vue.component('weekdays-traffic', require('./components/site-traffic/weekdays.vue').default);
Vue.component('days-month-traffic', require('./components/site-traffic/days_of_month.vue').default);


new Vue({
    el: '#app-traffic'
});
