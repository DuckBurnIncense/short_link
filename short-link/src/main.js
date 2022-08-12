import { createApp } from 'vue'
import App from './App.vue'

// fa
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

createApp(App)
.component('font-awesome-icon', FontAwesomeIcon)
.mount('#app');
