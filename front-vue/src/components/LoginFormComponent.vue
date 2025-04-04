<script>
import { loginUser } from '../services/authService'; // Importa el servicio

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: ''
    };
  },
  methods: {
    async handleLogin() {
      if(!this.email || !this.password){
        this.errorMessage = "Por favor, ingresa un correo y contraseña válido";
        return;
      }


      try {
        // Llamamos al servicio de login
        const response = await loginUser(this.email, this.password);
        console.log("Login exitoso:", response);
        // Puedes guardar el token o los datos del usuario en el estado global
        // Si usas Vuex, aquí podrías hacer un commit para guardar los datos
        this.$router.push('/dashboardUser'); // Redirigir a la página de inicio después de un login exitoso
      } catch (error) {
        // Si ocurre un error, mostrar un mensaje
        console.error("Error de login:", error);
        this.errorMessage = 'Email o contraseña incorrectos';
      }
    }
  }
};
</script>

<template>
  <div class="container mt-4">
      <h2>Loguéate</h2>
    <div>
      <form @submit.prevent="handleLogin" class="bg-lught p-4 rounded shadow">
        <div class="mb-3">
          <input v-model="email" type="email" placeholder="Email" class="form-control" required />
        </div>

        <div class="mb-3">
          <input v-model="password" type="password" placeholder="Password" class="form-control" required />
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
      </form>

      <p v-if="errorMessage">{{ errorMessage }}</p>
    </div>
  </div>
</template>
