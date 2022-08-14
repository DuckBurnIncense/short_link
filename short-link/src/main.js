import { createApp } from 'vue';
import App from './App.vue';

// fa
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

if (import.meta.env.DEV) document.title = '开发环境 - ' + document.title;

createApp(App)
.component('font-awesome-icon', FontAwesomeIcon)
.mount('#app');
