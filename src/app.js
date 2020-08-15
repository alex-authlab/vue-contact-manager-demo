// 1. Import the libraries
import Vue from 'vue';
import VueRouter from 'vue-router';

import {
    Button,
    ButtonGroup,
    Table,
    TableColumn,
    Pagination,
    Dialog,
    Form,
    FormItem,
    Input,
    Select,
    Option,
    Notification,
    Loading
} from 'element-ui';

Vue.use(Button);
Vue.use(ButtonGroup);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Pagination);
Vue.use(Dialog);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(Input);
Vue.use(Select);
Vue.use(Option);

Vue.prototype.$notify = Notification;

Vue.use(Loading.directive);
Vue.prototype.$loading = Loading.service;

locale.use(lang);
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';

// 2. Import the route components
import RootApp from './RootApp.vue';
import AllContacts from './Components/AllContacts';
import ViewContact from './Components/ViewContact';

Vue.use(VueRouter);

// 2. Define some routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: AllContacts
    },
    {
        path: '/contact/:id/view',
        name: 'viewContact',
        component: ViewContact,
        props: true
    }
];

// 3. Create the router instance and pass the `routes` option
const router = new VueRouter({
    routes,
    template: RootApp
});

// We need some mixins so we can use globally
Vue.mixin({
    data() {
        return {
            config: window.vue_contact_vars
        }
    },
    methods: {
        $request(type, path, data = {}) {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: this.config.api_url + '/' + path,
                    data: data,
                    type: type,
                    beforeSend: ( xhr ) => {
                        xhr.setRequestHeader( 'X-WP-Nonce', this.config.nonce );
                    }
                })
                    .then(response => resolve(response))
                    .fail(errors => reject(errors.responseJSON))
            });
        }
    }
});

// 4. Create and mount the root instance.
const app = new Vue({
    el: '#vue_contact_app',
    render: h => h(RootApp),
    router: router
});
