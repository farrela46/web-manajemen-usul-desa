import { createApp } from "vue";
import App from "./App.vue";
import store from "./store";
import router from "./router";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import ArgonDashboard from "./argon-dashboard";
import Notifications from '@kyvg/vue3-notification'
// import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'

const appInstance = createApp(App);
const vuetify = createVuetify({
    components,
    directives,
  })


appInstance.use(store);
appInstance.use(router);
appInstance.use(Notifications);
appInstance.use(vuetify);
appInstance.use(ArgonDashboard);
appInstance.mount("#app");
