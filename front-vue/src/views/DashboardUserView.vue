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
      appliedOffers: [], //aquí se guardarán las ofertas en las que se postule el usuario
      successMessage: '',
      selectedSubcategory: null,
      filteredOffers: [],
      userId: null,
      currentOfferIndex: 0, //lleva el control de la oferta que estás viendo, para luego que el usuario pueda ir cambiándolas
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

    async fetchApplications(){
      try {
        const response = await applicationService.getUserApplications(this.userId);
        console.log('Ofertas aplicadas: ', response.data);
        this.appliedOffers = response.data || []; //guardar postulaciones en el estado local

        localStorage.setItem('appliedOffers', JSON.stringify(this.appliedOffers)); //guardar las posulaciones en el localStorage

      } catch (error) {
        console.log('Error al obtener las postulaciones: ', error);
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
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al postularte',
          confirmButtonText: 'Aceptar',
        });
      }
    },

    prevOffer(){
      if(this.currentOfferIndex > 0){
        this.currentOfferIndex--;
      }
    },

    nextOffer(){
      if(this.currentOfferIndex < this.appliedOffers.length -1){
        this.currentOfferIndex++;
      }
    },

    async removeApplication(id){
      const result = await Swal.fire({
        title: '¿Estás seguro',
        text: '¿Quieres cancelar tu postulación a esta oferta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No, mantener',
        reverseButtons: true
      });

      if(result.isConfirmed){
        this.appliedOffers = this.appliedOffers.filter(o => o.id !==id);
          if(this.currentOfferIndex >= this.appliedOffers.length){
            this.currentOfferIndex = Math.max(this.appliedOffers.length -1, 0);
          }

          Swal.fire(
            'Postulación cancelada!',
            'Te has desinscrito de la oferta correctamente',
            'success'
          );
      }

    },

    //Método para hacer el logout
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
  <!--Mensaje de éxito-->
  <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
    {{ successMessage }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>

  <!--Admin-->
  <div v-if="isAdmin">
    <div class="card mb-4">
      <div class="card-header bg-dark text-white">Administración de usuarios</div>
      <div class="card-body">
        <UserComponent />
      </div>
    </div>
  </div>

  <!--Perfil + Ofertas activas-->
  <div class="row g-4">
    <!-- Perfil -->
    <div class="col-md-4">
      <ProfileCardComponent :user="user" />
    </div>

    <!-- Ofertas activas + Buscador -->
    <div class="col-md-8">
      <!-- Buscador en la parte superior de las ofertas -->
      <div class="d-flex justify-content-center">
        <CategoryFilterComponent
          :categories="categories"
          @subcategory-selected="selectSubcategory"
          class="w-75"
        />
      </div>

      <!-- Ofertas activas -->
       <div class="col-md-15">
      <AppliedOffersComponent
        v-if="appliedOffers && appliedOffers.length"
        :applied-offers="appliedOffers"
        :currentOfferIndex="currentOfferIndex"
        @prev-offer="prevOffer"
        @next-offer="nextOffer"
        @remove-application="removeApplication"
      />
      </div>
    </div>
  </div>

  <!--Cursos y mensajes-->
  <div class="row g-4 mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Tus cursos</h5>
          <ul>
            <li>Cursos</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Mensajes</h5>
          <ul>
            <li>Mensajes</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

<style scoped>
.card {
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease;
}

.card:hover{
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn.apply{
  background-color: #007bff;
  border-color: #007bff;
  padding: 12px 20px;
  font-size: 1.1rem;
  border-radius: 5px;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

.btn.apply:hover{
  background-color: #218838;
  border-color: #1e7e34;
}

.card-body {
  padding: 1.5rem;
}

.card-text{
  font-size: 1rem;
  color: #555;
  line-height: 1.5;
  text-align: justify;
}

.card-title {
  font-size: 1.25rem;
}

.btn.cv {
  background-color: #007bff;
  border-color: #007bff;
  box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
  transition: all 0,3s ease;
}

.btn.cv:hover{
  background-color: #0056b3;
  border-color: #0056b3;
}
.profile-card{
  display: flex;
  flex-direction: row-reverse;
  align-items: center;
  gap: 20px;
}

.profile-photo {
  border-radius: 50%;
  border: 3px solid #f0f0f0;
  width: 120px;
  height: 120px;
  object-fit: cover;
  margin-bottom: 15px;
}

.category-item {
  text-align: center;
  border: 2px solid #ddd;
  padding: 15px;
  border-radius: 100px; /* Tarjetas más redondeadas */
  /*transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;*/
}

.category-icon:hover{
  transform: scale(1.05);
  box-shadow: 0 8px 16px rgb(0, 0, 0, 0.2);
  background-color: #f1f1f1;
}

.category-icon {
  font-size: 2rem;
  color: #007bff;
  transition: color 0.3s ease;
}

.category-link {
  cursor: pointer;
}

.category-icon:hover {
  color: #0056b3;
}

.btn.prevOffer:disabled{
  background-color: #ccc;
}

</style>
