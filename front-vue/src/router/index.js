import CreateUserFormComponent from "@/components/CreateUserFormComponent.vue";
import EditUserFormComponent from "@/components/EditUserFormComponent.vue";
import HomeView from "@/views/HomeView.vue";
import UserView from "@/views/UserView.vue";
import { createRouter, createWebHistory } from "vue-router";

const routes = [
    { path: "/", component: HomeView, name: "home" },
    { path: "/users", component: UserView, name: "users" },
    { path: "/create", component: EditUserFormComponent, name: "createUser"},
    { path: "/edit/:id", component: EditUserFormComponent, name: "editUser"},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
