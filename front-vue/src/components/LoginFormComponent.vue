<script>
import { loginUser } from '../services/authService'; // Importa el servicio

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
      fromRegistration: false //variable que controla el mensaje
    };
  },
  created(){
    //verificar si vienes del registro
    const fromRegistration = this.$route.query.fromRegistration;
    if(fromRegistration){
      this.fromRegistration = true;
    }
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

        localStorage.setItem('userId', response.id);
        //localStorage.setItem('token', response.jwt); //guardas el token (en el controlador en symfpony al token le has llamado jwt) para poder controlar rutas y quién entra y quién no

        //guardar el token en localStorage
        localStorage.setItem('token', response.token);

        //Decodificar el token JWT para obtener los datos del usuario
        //const tokenPayLoad = JSON.parse(atob(response.jwt.split('.')[1]));
        const tokenPayLoad = JSON.parse(atob(response.token.split('.')[1]));
        console.log('Token Payload: ', tokenPayLoad);

        //verificar el rol del usuario
        const userRole = tokenPayLoad.role; 
        console.log('User Role: ', userRole);

        if(userRole === 'ROLE_USER'){
          this.$router.push('/dashboardUser');
        }else if(userRole === 'ROLE_COMPANY'){
          this.$router.push('/dashboardCompany');
        }else{
          //manejar casos de error
          console.log('Error: rol desconocido');
          this.$router.push('/api/login');
        }

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
      <!--Si viene del registro, mostrar mensaje para terminar de registrarse-->
      <div v-if="fromRegistration" class="alert alert-info">
        <strong>¡Bienvenido/a!</strong>Para finalizar tu registro, por favor inicia sesión.
      </div>
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
