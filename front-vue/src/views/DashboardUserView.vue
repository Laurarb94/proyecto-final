<script>
import { logoutUser } from '@/services/authService';
import { getUserById } from '@/services/userService';

export default {
  name: 'DashboardUserView',

  data() {
    return {
      user:{}, //inicializas como objeto vacío
    };
  },
  created(){
    this.fetchUserData(); //llamas a la función para que te devuleva los datos del usuario
  },
  methods: {
    //Método para obtener los datos del usuario al hacer login
    async fetchUserData() {
      try{
        const userId = localStorage.getItem('userId'); //obtienes el userId desde el localStorage

        if(userId){
          this.user = await getUserById(userId);
          console.log("usuario cargado: ", this.user);

        }else{
          console.log("Usuario no autenticado");
        }
      }catch(error){
        console.log("Error al obtener los datos del usuario: ", error);
      }
    },

    //Método para hacer el logout
    async logout(){
      try{
        //llamar al servicio del logout
        await logoutUser();

        //Limpiar el localStorage:
        localStorage.removeItem("id");
        localStorage.removeItem("token");

        //Redirigir a la página principal
        this.$router.push("/");
      }catch(error){
        console.log("Error al hacer el logout: ", error);
      }
    }
  }
};


</script>

<template>
  <div class="container my-4">
    <div class="row">

      <!-- Barra lateral izquierda (Perfil y opciones) -->
      <div class="col-md-3">
        <div class="card">
          <img v-if="user.photo" :src="`http://localhost:8000/uploads/profile_photos/${user.photo}`" alt="Foto de perfil" class="card-img-top img-fluid" />
          <div class="card-body">
            <h5 class="card-title" v-if="user.name">Bienvenido/a, {{ user.name }}</h5>
            <p class="card-text">Biografía: {{ user.biography }} </p>
            <a v-if="user.cv" :href= "`http://localhost:8000/uploads/cvs/${user.cv}`" class="btn btn-primary" target="_blank">Ver CV</a>
          </div>
        </div>
      </div>

      <!-- Centro: Ofertas activas -->
      <div class="col-md-6">
        <h2>Ofertas de empleo activas</h2>
        <div class="card-deck">
          <div class="card">
            <div class="card-body"> <!--usar vfor cuando tengas el código en symfony de las ofertas-->
              <h5 class="card-title">Título de la oferta</h5>
              <p class="card-text">Descripción de la oferta</p>
              <p><strong>Empresa:</strong> Empresa de la oferta</p>
              <p><strong>Ubicación:</strong> Ubicación de la oferta</p>
              <a href="#" class="btn btn-success">Postúlate</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Barra lateral derecha: Cursos y mensajes -->
      <div class="col-md-3">
        <h3>Cursos disponibles</h3>
        <div class="list-group">
          <div class="list-group-item"> <!--vfor cuando tengas el código-->
            <h5>Nombre del curso</h5>
            <p>Descripción del curso</p>
            <a href="#" class="btn btn-info btn-sm">Ver detalles</a>
          </div>
        </div>

        <h3 class="mt-4">Mensajes</h3>
        <div class="list-group">
          <div class="list-group-item"> <!--vfor cuando tengas el código-->
            <p>Texto del mensaje</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  margin-bottom: 20px;
}

.card-deck {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.list-group-item {
  padding: 10px;
  margin-bottom: 10px;
}

.card-title {
  font-size: 1.25rem;
}
</style>
