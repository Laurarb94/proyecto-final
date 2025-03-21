import HomeView from "@/views/HomeView.vue";
import UserView from "@/views/UserView.vue";
import { createRouter, createWebHistory } from "vue-router";

const routes = [
    { path: "/", component: HomeView, name: "home" },
    { path: "/users", component: UserView, name: "users" },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
