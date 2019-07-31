import './src_classes/Content/Import.js';


// Global Event
import {
    EventBus
} from './global_events/bus';
window.EventBus = EventBus;
// Global Event

import contentListMixin from './mixin/content_list.js'
Vue.mixin(common);
import './content_items/components';

// Import draggable component for items
Vue.component('draggable', require('vuedraggable').default);

// List Structure Representation Component
Vue.component('list-style', require('./components/content-form/component_items/list/style.vue').default);
Vue.component('list-preview', require('./components/content-form/component_items/list/preview.vue').default);
// List Structure Representation Component


// Modal Item Components
Vue.component('phar', require('./components/content-form/sub-items/paragraph.vue').default);
Vue.component('tbl', require('./components/content-form/sub-items/table.vue').default);
Vue.component('list', require('./components/content-form/sub-items/list.vue').default);
Vue.component('com-image', require('./components/content-form/sub-items/image.vue').default);
// Modal Item Components


Vue.component('content-form', require('./components/content-form/sub-items/form.vue').default).mixin(contentListMixin);
Vue.component('main-item', require('./components/content-form/main-item.vue').default);