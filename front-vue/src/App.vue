<script setup>
import { ref, onMounted, onUnmounted} from 'vue';
import { useRouter } from 'vue-router';
import LogoutButtonComponent from './components/LogoutButtonComponent.vue';
import { useAuth } from './composables/useAuth';

const { isAuthenticated, logout} = useAuth();
const router = useRouter();

const handleLogout = () => {
  logout();
  router.push('/');
}



/* Verifica si el usuario está logueado
const user = ref(localStorage.getItem('userId'));  // Si el 'userId' está en localStorage, el usuario está autenticado
const router = useRouter();

//Escuchar cambios en el localStorage para mantener el estado reactivo
const handleStorageChange = () => {
  user.value = localStorage.getItem('userId');
};

onMounted(()=>{
  window.addEventListener('storage', handleStorageChange);
});

onUnmounted(()=>{
  window.addEventListener('storage', handleStorageChange);
});

// Función para hacer log out
const logout = () => {
  localStorage.removeItem('userId'); // Eliminar el 'userId' de localStorage al hacer logout
  localStorage.removeItem('token');
  user.value = null;  // El usuario ya no está logueado
  router.push('/');  // Redirigir al inicio después de cerrar sesión
};
*/

</script>

<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div class="container">
        <RouterLink to="/" class="navbar-brand">Mi empleo</RouterLink>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item" v-if="!isAuthenticated">
              <RouterLink to="/api/login" class="nav-link">Log in</RouterLink>
            </li>
            <li class="nav-item" v-if="!isAuthenticated">
              <RouterLink to="/registerUser" class="nav-link">Regístrate</RouterLink>
            </li>
            <li class="nav-item" v-if="isAuthenticated">
              <button @click="handleLogout" class="btn btn-link nav-link">Cerrar sesión</button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Página Principal (HomeView) -->
    <RouterView />

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
      <p>© 2025 Mi TFG - Todos los derechos reservados</p>
    </footer>
    
  </div>

</template>

<style scoped>
body {
  background-color: #f4f7fa;
  color: #2c3e50;
  font-family: 'Roboto', sans-serif;
}


.navbar{
  background-color: #f8f9fa;
  border-bottom: 1px solid #e0e0e0;
}

.navbar-nav .nav-link{
  color: #333;
  font-weight: 500;
  transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover{
  color: #0056b3;
}

.navbar-brand{
  font-size: 1.5rem;
  font-weight: bold;
  color: #007bff;
}

footer{
  background-color: #343a40;
  color: white;
  text-align: center;
  padding: 20px 0;
}



</style>
