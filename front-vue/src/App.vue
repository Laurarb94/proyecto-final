<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import LogoutButtonComponent from './components/LogoutButtonComponent.vue';

// Verifica si el usuario está logueado (por ejemplo, usando localStorage)
const user = ref(localStorage.getItem('userId'));  // Si el 'userId' está en localStorage, el usuario está autenticado
const router = useRouter();

// Función para hacer log out
const logout = () => {
  localStorage.removeItem('userId'); // Eliminar el 'userId' de localStorage al hacer logout
  user.value = null;  // El usuario ya no está logueado
  router.push('/');  // Redirigir al inicio después de cerrar sesión
};

</script>

<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <ul class="navbar-nav d-flex flex-row">
          <!--Si el usuario no está logueado-->
          <li class="nav-item">
            <RouterLink v-if="!user" to="#" class="nav-link">Quiénes somos</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink v-if="!user" to="#" class="nav-link">Por qué elegirnos</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink v-if="!user" to="#" class="nav-link">Contáctanos</RouterLink>
          </li>

          <!--Si el usuario está logueado-->
          <li class="nav-item">
            <RouterLink v-if="user" to="#" class="nav-link">Mensajes</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink v-if="user" to="#" class="nav-link">Cursos</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink v-if="user" to="#" class="nav-link">Ajustes</RouterLink>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <!--Mostrar login si no hay usuario logueado, y logout si el usuario está logueado-->
            <RouterLink v-if="!user" to="/api/login" class="nav-link">Log in</RouterLink>
            <LogoutButtonComponent v-if="user" /> 
          </li>
          <li class="nav-item">
            <!--Mostrar registrarse si no hay usuario logueado-->
            <RouterLink v-if="!user" to="/registerUser" class="nav-link">Regístrate</RouterLink>
            <RouterLink v-if="!user" to="/registerCompany">¿Eres una empresa? Regístrate aquí</RouterLink>
          </li>
        </ul>
      </div>
    </nav>

    <RouterView />

    <!--
    <footer class="bg-dark text-white text-center py-3 mt-4">
      <p>© 2025 Mi TFG - Todos los derechos reservados</p>

    </footer>
  -->

  </div>
</template>

<style scoped>

</style>
