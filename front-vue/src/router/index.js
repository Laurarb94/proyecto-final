import { createRouter, createWebHistory } from "vue-router";
import EditUserFormComponent from "@/components/EditUserFormComponent.vue";
import RegistrationFormComponent from "@/components/RegistrationFormComponent.vue";
import HomeView from "@/views/HomeView.vue";
import UserView from "@/views/UserView.vue";
import DashboardUserView from "@/views/DashboardUserView.vue";
import LoginFormComponent from "@/components/LoginFormComponent.vue";
import DashboardCompanyView from "@/views/DashboardCompanyView.vue";
import RegistrationCompanyFormComponent from "@/components/RegistrationCompanyFormComponent.vue";
import LogoutButtonComponent from "@/components/LogoutButtonComponent.vue";
import ProfileCardComponent from "@/components/ProfileCardComponent.vue";
import JobOfferListComponent from "@/components/JobOfferListComponent.vue";
import MyOffersView from "@/views/MyOffersView.vue";


const routes = [
    { path: "/", component: HomeView, name: "home" },
    { path: "/users", component: UserView, name: "users" },
    { path: "/create", component: EditUserFormComponent, name: "createUser"},
    { path: "/edit/:id", component: EditUserFormComponent, name: "editUser"},
    { path: "/registerUser", component: RegistrationFormComponent, name: "registrationUser"},
    { path: "/registerCompany", component: RegistrationCompanyFormComponent, name: "registrationCompany"},
    { path: "/api/login", component: LoginFormComponent, name: "login"},
    { path: "/api/logout", component: LogoutButtonComponent, name: "logout"},
    { path: "/dashboardUser", component: DashboardUserView, name: "dashboardUser", meta: { requiresAuth: true}},//con requireAutrh proteges la ruta
    { path: "/dashboardCompany", component: DashboardCompanyView, name: "dashboardCompany", meta: {requiresAuth: true} },
    { path: "/profile/:userId", component: ProfileCardComponent, name: "profile", props: true },
    { path: "/jobOfferList", component: JobOfferListComponent, name: "jobOfferList"},
    { path: "/myOffers", component: () => import('@/views/MyOffersView.vue'), name: "myOffers"}
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

//Glocal Guard para rutas protegidas:
router.beforeEach((to, from, next)=>{
  const requiresAuth = to.matched.some(record=> record.meta.requiresAuth);
  const token = localStorage.getItem("token");

  //Si la ruta require autenticación y no hay token, redirige al login
  if(requiresAuth && !token){
    next({ name: "login"});
  }else{ //si sí que hay token, sigue con la navegación
    next();
  }
});

export default router;
