<script>
import { logoutUser } from '@/services/authService';
import { getUserById } from '@/services/userService';
import { useAuth } from '@/composables/useAuth';
import jobOfferService from '@/services/jobOfferService';
import categoryService from '@/services/categoryService';
import UserComponent from '@/components/UserComponent.vue';
import ProfileCardComponent from '@/components/ProfileCardComponent.vue';
import CategoryFilterComponent from '@/components/CategoryFilterComponent.vue';
import AppliedOffersComponent from '@/components/AppliedOffersComponent.vue';
import applicationService from '@/services/applicationService';
import Swal from 'sweetalert2';
import OfferListComponent from '@/components/OfferListComponent.vue';
import router from '@/router';

export default {
  name: 'DashboardUserView',

  components: {
    UserComponent,
    AppliedOffersComponent,
    CategoryFilterComponent,
    OfferListComponent,
    ProfileCardComponent
  },

  props: {
    offer: Object, 
  },

  data() {
    return {
      
      user:{}, //inicializas como objeto vacío
      offers: [], 
      categories: [],
      subcategories: [],
      successMessage: '',
      selectedSubcategory: null,
      selectedSubcategoryId: null,
      filteredOffers: [],
      userId: null,    
      currentView: 'offers', // Estado inicial, que indica que las ofertas se muestran al inicio
      searchQuery: '', // Aquí se almacena la consulta de búsqueda
      currentPage: 1, // Página actual para la paginación
      totalPages: 1, // Total de páginas para la paginación
    };
  },

  created(){
    this.fetchUserData(); //llamas a la función para que te devuleva los datos del usuario
    this.fetchCategories();
    this.fetchOffers(); 
  },

  computed: {
      isAdmin() {
        const role = localStorage.getItem('userRole'); //acceder al rol almacenado
        return role === 'ROLE_ADMIN';
      }
  },

  methods: {
    // Método para cambiar la vista a 'profile'
    goToProfile() {
      // Verifica que el usuario esté cargado antes de redirigir
      if (this.user) {
        // Redirige con el parámetro 'user' a la vista del perfil
        this.$router.push({ name: 'profile', params: { userId: this.user.id } });
      } else {
        console.error('No se pudo encontrar al usuario');
      }
  },

    // Método para cambiar la vista a 'offers'
    goToOffers() {
      this.$router.push({ name: 'myOffers' });
    },

    // Método para cambiar la vista a 'courses'
    goToCourses() {
      this.currentView = 'courses';
    },

    // Método para cambiar la vista a 'messages'
    goToMessages() {
      this.currentView = 'messages';
    },

    //Método para obtener los datos del usuario al hacer login
    async fetchUserData() {
      try{
        const userId = localStorage.getItem('userId'); //obtienes el userId desde el localStorage
        console.log('USER ID desde LOCALSTORAGE: ', userId);

        if(userId){
          this.userId = parseInt(userId);
          this.user = await getUserById(userId);

          //cargar las postulacionees guardas desde el localStorage
          const storedApplications = localStorage.getItem('appliedOffers');
          if(storedApplications){
            this.appliedOffers = JSON.parse(storedApplications); //cargar desde el localStorage
          }else{
            await this.fetchApplications();
          }
          
        }else{
          console.log("Usuario no autenticado");
        }
      }catch(error){
        console.log("Error al obtener los datos del usuario: ", error);
      }
    },

     async fetchCategories(){
      try {
        this.categories = await categoryService.getAll();
      } catch (error) {
        console.log('Error al obtener las categorías: ', error);
      }
     },

     async fetchOffers(){
      try {
        const response = await jobOfferService.getAll();
        this.offers = response;
      } catch (error) {
        console.log('Error al obtener las ofertas: ', error);
      }
     },

    selectSubcategory(subcategory){
      this.selectedSubcategoryId = subcategory.id;
      this.fetchFilteredOffers();
    },

    async fetchFilteredOffers(){
      try {
        const response = await jobOfferService.getAll();
        this.filteredOffers = response.filter(offer =>
          offer.subcategory && offer.subcategory.id === this.selectedSubcategoryId
        );
      } catch (error) {
        console.log('Error al filtrar las ofertas: ', error);
      }
    },

    handleApplyOffer(offerId){
      const offerToApply = this.offers.find(offer=>offer.id === offerId);
      if(offerToApply){
        this.apply(offerId);
      }
    },

    async apply(offerId){
      try {
        const response = await applicationService.applyToOffer(offerId, this.userId);
        Swal.fire({
          icon: 'success',
          title: 'Postulación exitosa',
          text: response.data.message,
          confirmButtonText: 'Aceptar',
        });

        // Mover la oferta a "mis ofertas postuladas"
        const appliedOffer = this.offers.find(offer => offer.id === offerId);
        if (appliedOffer) {
          this.appliedOffers.push(appliedOffer);
          this.offers = this.offers.filter(offer => offer.id !== offerId);
          localStorage.setItem('appliedOffers', JSON.stringify(this.appliedOffers));
        }

        this.$router.push({ name: 'myOffers' });


      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al postularte',
          confirmButtonText: 'Aceptar',
        });
      }
    },

    clearFilter(){
      this.selectedSubcategoryId = null;
      this.filteredOffers = [];
    },

    async logout(){
      try{
        //llamar al servicio del logout
        await logoutUser();

        //Limpiar el localStorage:
       // localStorage.removeItem("id");
        localStorage.removeItem("token");
        localStorage.removeItem('userId');
        localStorage.removeItem('appliedOffers'); // Limpiar las postulaciones

        //Redirigir a la página principal
        this.$router.push("/");
      }catch(error){
        console.log("Error al hacer el logout: ", error);
      }
    }

  }, //cierre de los métodos

};


</script>

<template>
 <div class="container my-4">

    <!-- Banner de Bienvenida/Introducción, solo se ve cuando no hay una oferta seleccionada -->
    <div class="intro-container fade-in" v-if="!selectedSubcategoryId">
      <div class="intro-banner">
      <h2>Bienvenida/o a nuestra plataforma de búsqueda de empleo</h2>
      <p>Encuentra las mejores ofertas de empleo, postúlate fácilmente y gestiona tu perfil profesional.</p>
      <p>Conéctate con personas que, al igual que tú, están listas para reinventarse profesionalmente.</p>
      <p>Apúntate a nuestros cursos y destaca en tu próximo empleo. ¡El futuro está a tu alcance!</p>
    </div>
    <img src="../assets/images/fotoInicio.png" alt="búsqueda de empleo" class="intro-image" />
    </div>


    <!-- Ofertas de empleo, resultados filtrados cuando clicas en subcategoría -->
    <div class="row justify-content-center fade-in">
      <div class="col-md-4" v-for="offer in filteredOffers" :key="offer.id">
        <div class="job-card">
          <div class="job-header">
            <h5>{{ offer.title }}</h5>
          </div>
          <p class="job-description">{{ offer.description }}</p>
          <button class="btn btn-primary" @click="handleApplyOffer(offer.id)">Aplicar</button>
        </div>
      </div>
    </div>


    <!-- Barra de búsqueda (filtros) -->
    <div class="search-bar">
      <CategoryFilterComponent
          :categories="categories"
          @subcategory-selected="selectSubcategory"
          class="w-75"
      />
    </div>

    <!--Limpiar el filtro-->
    <div v-if="selectedSubcategoryId" class="mb-3 text-end">
      <button class="btn btn-outline-secondary" @click="clearFilter">Limpiar filtro</button>
    </div>

    <!-- Menú de Usuario -->
    <div class="user-menu">
      <button class="btn btn-secondary" @click="goToProfile">Mi Perfil</button>
      <button class="btn btn-secondary" @click="goToOffers">Ofertas de Empleo</button>
      <button class="btn btn-secondary" @click="goToCourses">Mis Cursos</button>
      <button class="btn btn-secondary" @click="goToMessages">Mensajes</button>
    </div>

  </div>

</template>

<style scoped>
.intro-container {
  display: flex;
  align-items: center;
  gap: 20px;
  width: 100%;
}

/* Banner de introducción */
.intro-banner {
  padding: 2rem;
  border-radius: 10px;
  margin-bottom: 2rem;
  text-align: justify;
  flex: 1;
}

.intro-image{
  width: 300px;
  max-width: 200%;
}

.intro-banner h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.intro-banner p {
  font-size: 1.1rem;
  color: #555;
}


/* Barra de búsqueda */
.search-bar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 2rem;
}

.search-bar input {
  width: 75%;
  border-radius: 25px;
  padding: 10px;
  font-size: 1.1rem;
}

.search-bar button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.search-bar button:hover {
  background-color: #0056b3;
}

/* Menú de usuario */
.user-menu {
  display: flex;
  justify-content: flex-start;
  gap: 15px;
  margin-bottom: 2rem;
}

.user-menu button {
  background-color: #f1f1f1;
  color: #333;
  border: none;
  padding: 10px 20px;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.user-menu button:hover {
  background-color: #007bff;
  color: white;
}

/* Tarjetas de ofertas de empleo */
.job-card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 20px;
  transition: box-shadow 0.3s ease;
}

.job-card:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.job-header {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.company-logo {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 15px;
}

.job-card h5 {
  font-size: 1.2rem;
  font-weight: bold;
  margin: 0;
}

.job-description {
  font-size: 1rem;
  color: #555;
  margin-bottom: 1rem;
}

.job-card button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 25px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.job-card button:hover {
  background-color: #0056b3;
}

.fade-in {
  animation: fadeIn 0.7s ease-in-out both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}


</style>
