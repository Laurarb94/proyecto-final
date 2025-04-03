
import EditUserFormComponent from "@/components/EditUserFormComponent.vue";
import RegistrationFormComponent from "@/components/RegistrationFormComponent.vue";
import HomeView from "@/views/HomeView.vue";
import UserView from "@/views/UserView.vue";
//import { name } from "@vue/eslint-config-prettier/skip-formatting";
import { createRouter, createWebHistory } from "vue-router";

const routes = [
    { path: "/", component: HomeView, name: "home" },
    { path: "/users", component: UserView, name: "users" },
    { path: "/create", component: EditUserFormComponent, name: "createUser"},
    { path: "/edit/:id", component: EditUserFormComponent, name: "editUser"},
    { path: "/registerUser", component: RegistrationFormComponent, name: "registrationUser"},
    //{ path: "/dashboardUser", component: DashboardUserView, name: "dashboardUser"}

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
