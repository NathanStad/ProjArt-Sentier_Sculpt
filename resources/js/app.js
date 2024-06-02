import { createApp } from 'vue/dist/vue.esm-bundler.js'
import VueApp from "./app.vue"
import axios from 'axios';


// Récupère le token CSRF depuis la balise meta
const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

// Configure Axios pour inclure le token CSRF dans les en-têtes
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

createApp(VueApp).mount('#app')