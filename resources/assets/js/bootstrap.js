import Vue from 'vue';
import Popper from 'popper.js';
import swal from 'sweetalert2';
import jQuery from 'jquery';
import 'parsleyjs';
import Echo from 'laravel-echo';

// Setup Popper, jQuery, sweetalert2, and Bootstrap.
window.Popper = Popper;
window.$ = jQuery;
window.swal = swal;
require('bootstrap');

/**
 * Import components.
 */
import App from './app.js';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '152ebce801ec06bb205f',
    cluster: 'us2',
    encrypted: true,
});

// Register global components.
Vue.component('edit-note', require('./components/EditNote.vue'));
Vue.component('chat-messages', require('./components/chat/ChatMessages.vue'));
Vue.component('chat-form', require('./components/chat/ChatForm.vue'));

/**
 * Kickstart the app.
 */
window.$app = new Vue(App);
$app.csrfToken = token;