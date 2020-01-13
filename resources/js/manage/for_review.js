import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert);

Vue.component('doc-for-review', require('.././components/manage/for_review/list.vue').default);
Vue.component('comments', require('.././components/manage/documents/comments.vue').default);
Vue.component('button-review', require('.././components/manage/for_review/button_review.vue').default);

new Vue({
    el: '#app-document'
});
