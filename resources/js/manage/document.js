import "@sintexph/vue-lib"
Vue.mixin(toastHelper);Vue.mixin(httpAlert);

import ".././content-form.js";
import contentListMixin from '.././mixin/content_list.js';


Vue.mixin(require('../mixin/document_access').default);


import {
    Document
} from '.././src_classes/Document';
window.Document = Document;

import {
    DocumentVersion
} from '.././src_classes/DocumentVersion';
window.DocumentVersion = DocumentVersion;


import IdleVue from 'idle-vue'
const eventsHub = new Vue()
Vue.use(IdleVue, {
    eventEmitter: eventsHub,
    idleTime: 5000
})


window.EVENT_BUS = new Vue()


Vue.component('version-form', require('.././components/manage/documents/forms/version-form.vue').default);
Vue.component('document-form', require('.././components/manage/documents/forms/document-form.vue').default);
Vue.component('access-form', require('.././components/manage/documents/forms/access-form.vue').default);



Vue.component('manage-documents', require('.././components/manage/documents/list.vue').default);
Vue.component('new-version', require('.././components/manage/documents/new_version.vue').default).mixin(contentListMixin);
Vue.component('edit-version', require('.././components/manage/documents/edit_version.vue').default).mixin(contentListMixin);

Vue.component('change-status', require('.././components/manage/documents/change-status.vue').default);
Vue.component('document-create', require('.././components/manage/documents/create_document.vue').default).mixin(contentListMixin);
Vue.component('document-edit', require('.././components/manage/documents/edit_document.vue').default);
Vue.component('add-reference', require('.././components/manage/documents/add_reference.vue').default);
Vue.component('doc-ver-attachments', require('.././components/manage/documents/version_attachments.vue').default);
Vue.component('upload-file', require('.././components/manage/documents/upload.vue').default);
Vue.component('current-version', require('.././components/manage/documents/current_version.vue').default);
Vue.component('edit-access', require('.././components/manage/documents/edit_access.vue').default);
Vue.component('view-version', require('.././components/manage/documents/view_version.vue').default);
Vue.component('lock-document', require('.././components/manage/documents/lock_document.vue').default);
Vue.component('reference-documents', require('.././components/manage/documents/references.vue').default);
Vue.component('document-archive', require('.././components/manage/documents/archive.vue').default);
Vue.component('comments', require('.././components/manage/documents/comments.vue').default);

Vue.component('document-version-action', require('.././components/manage/documents/document_version_actions.vue').default);



new Vue({
    el: '#app-document'
});
