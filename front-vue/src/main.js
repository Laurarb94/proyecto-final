import './assets/main.css';
import router from "./router";
import "./index.css";

import { createApp } from 'vue'
import App from './App.vue'

//Importar bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Importa las librerías de Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { faUser, faHome, faEnvelope, faTrash, faUserPen, faPlus, faLaptop, faStaffSnake, faLightbulb,
    faGraduationCap, faBook, faPlane, faDiagramProject, faCalculator,
    faQuestionCircle
 } from '@fortawesome/free-solid-svg-icons' // Importa los íconos que quieras
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import '@fortawesome/fontawesome-svg-core/styles.css'

// Añadir los íconos a la librería
library.add(faUser, faHome, faEnvelope, faTrash, faUserPen, faPlus, faLaptop, faStaffSnake, faLightbulb, 
    faGraduationCap, faBook, faPlane, faDiagramProject, faCalculator, faQuestionCircle)

const app = createApp(App);
app.use(router);
app.mount("#app");
app.component('font-awesome-icon', FontAwesomeIcon) //registras el componente de fontawesome globalmente
