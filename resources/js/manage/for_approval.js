import "@sintexph/vue-lib"
Vue.mixin(httpAlert);

Vue.component('doc-for-approval', require('.././components/manage/for_approval/list.vue').default);
Vue.component('comments', require('.././components/manage/documents/comments.vue').default);
Vue.component('button-approve', require('.././components/manage/for_approval/button_approve.vue').default);

new Vue({
    el: '#app-document'
});
