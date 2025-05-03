<script>
import { logoutUser } from '@/services/authService';
import { getUserById } from '@/services/userService';
import jobOfferService from '@/services/jobOfferService';
import categoryService from '@/services/categoryService';
import applicationService from '@/services/applicationService';
import Swal from 'sweetalert2';
import { onBeforeRouteLeave } from 'vue-router';

export default {
  name: 'DashboardUserView',

  props: {
    offer: Object, 
  },

  data() {
    return {
      user:{}, //inicializas como objeto vacío
      offers: [], 
      categories: [],
      subcategories: [],
      selectedCategoryId: null,
      selectedSubcategoryId: null,
      appliedOffers: [], //aquí se guardarán las ofertas en las que se postule el usuario
      successMessage: '',
      categoryIcons: {
        'Informática': 'laptop',
        'Salud': 'staff-snake',
        'Marketing': 'lightbulb',
        'Ciencias Sociales': 'graduation-cap',
        'Humanidades': 'book',
        'Hosteleria y Turismo': 'utensils',
        'Matemáticas y Estadística':'diagram-project',
        'Ingenierias': 'tools',
      },
      userId: null,
      currentOfferIndex: 0, //lleva el control de la oferta que estás viendo, para luego que el usuario pueda ir cambiándolas
    };
  },

  created(){
    this.fetchUserData(); //llamas a la función para que te devuleva los datos del usuario
    this.fetchCategories();
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

    async fetchSubcategories() {
      const category = this.categories.find(cat => cat.id === parseInt(this.selectedCategoryId));
      //console.log("Categoría seleccionada:", JSON.stringify(category, null, 2));

      if(category && Array.isArray(category.subcategories)){
        this.subcategories = category.subcategories;
      }else{
        console.warn('Subcategorías no están en formato array, revisar backend o parseo');
        this.subcategories = [];
      }

      //console.log('Subcategorías (formato array de objetos):', this.subcategories);
    },

    async fetchFilteredOffers(){
      if(!this.selectedSubcategoryId) return;

      try {
        const response = await jobOfferService.getAll();
        
        console.log('Ofertas obtenidas: ', response);

        this.offers = response.filter(offer =>
          offer.subcategory && offer.subcategory.id === parseInt(this.selectedSubcategoryId)
        );

        console.log('Ofertas filtradas: ', this.offers);

      } catch (error) {
        console.log('Error al filtrar las ofertas: ', error);
      }

    },

    selectedSubcategory(subcategoryId) {
      this.selectedSubcategoryId = subcategoryId;
    },

    async apply(offerId){
      try {
        //console.log('USER ID: ', this.userId);
        const response = await applicationService.applyToOffer(offerId, this.userId);
        Swal.fire({
          icon: 'success',
          title: 'Postulación exitosa',
          text: response.data.message,
          confirmButtonText: 'Aceptar'
        });

        //Mover la oferta a "mis ofertas postuladas"
        const appliedOffers = this.offers.find(offer => offer.id === offerId); 
        if(appliedOffers){
          this.appliedOffers.push(appliedOffers); //moverla a la lista de ofertas aplicadas
          this.offers = this.offers.filter(offer => offer.id !== offerId); // Eliminarla de las ofertas disponibles
          localStorage.setItem('appliedOffers', JSON.stringify(this.appliedOffers));
        }

      } catch (error) {
        console.log('Error al aplicar: ', error);
        if(error.response && error.response.data){
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Error del servidor: ${error.response.data.error || 'Error desconocido'}`,
            confirmButtonText: 'Aceptar'
          });
        }else{
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al aplicar',
            confirmButtonText: 'Aceptar'
          });
        }
      }
    },

    async fetchApplications(){
      try {
        const response = await applicationService.getUserApplications(this.userId);
        this.appliedOffers = response.data; //guardar postulaciones en el estado local

        localStorage.setItem('appliedOffers', JSON.stringify(this.appliedOffers)); //guardar las posulaciones en el localStorage

      } catch (error) {
        console.log('Error al obtener las postulaciones: ', error);
      }
    },

    getCategoryIconKey(categoryName){
      const icon = this.categoryIcons[categoryName] || 'fa-question-circle'; //si no encuentra la categoría devuelve un icono por defecto
      return icon;
    },

    async removeApplication(offerId){
        // Mostrar un SweetAlert de confirmación antes de eliminar
        const result = await Swal.fire({
          title: '¿Estás seguro?',
          text: 'Una vez eliminada, no podrás recuperar esta postulación.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar',
          reverseButtons: true,
        });

        if (result.isConfirmed) {
          try {
            const response = await applicationService.deleteApplication(this.userId, offerId);
            Swal.fire({
              icon: 'success',
              title: 'Postulación eliminada',
              text: response.message,
              confirmButtonText: 'Aceptar',
            });

            // Eliminar la oferta de la lista de ofertas postuladas localmente
            this.appliedOffers = this.appliedOffers.filter(offer => offer.id !== offerId);

          } catch (error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'No se pudo eliminar la postulación',
              confirmButtonText: 'Aceptar',
            });
          }
        }
    },

    nextOffer(){
      if(this.currentOfferIndex < this.appliedOffers.length -1){
        this.currentOfferIndex++;
      }
    },

    prevOffer(){
      if(this.currentOfferIndex > 0){
        this.currentOfferIndex--;
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

  watch: {
  selectedCategoryId(newVal){
    this.fetchSubcategories();
    this.selectedSubcategoryId = null;
    console.log('Subcategorías: ', this.subcategories);
    this.offers = [];
  },
  selectedSubcategoryId(newVal){
    this.fetchFilteredOffers();
  }
}



};


</script>

<template>
  <div class="container my-4">
    <!--Mensaje de éxito-->
    <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
      {{ successMessage }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row">
      <!--Columna del perfil-->
      <div class="col-md-4">
        <div class="card">
          <img v-if="user.photo" :src="`http://localhost:8000/uploads/profile_photos/${user.photo}`" alt="Foto de perfil" class="card-img-top profile-photo" />
          <div class="card-body">
            <h5 class="card-title"> Bienvenido/a, {{ user.name }}</h5>
            <p class="card-text">Biografía: {{ user.biography }}</p>
            <a v-if="user.cv" :href= "`http://localhost:8000/uploads/cvs/${user.cv}`" class="btn btn-primary cv" target="_blank">Ver CV</a>
          </div>
        </div>
      </div>

      <!--Columna de ofertas-->
      <div class="col-md-8">
        <!--Filtro de categorías con iconos-->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Explora las categorías y encuentra tu trabajo ideal</h5>
            <div class="row">
              <div class="col-6 col-md-3" v-for="category in categories" :key="category.id">
                <div class="category-item">
                  <div class="category-link" @click="selectedCategoryId = selectedCategoryId === category.id ? null : category.id">
                    <div class="category-icon mx-auto">
                      <font-awesome-icon :icon="['fas', getCategoryIconKey(category.name)]" />
                    </div>
                    <p class="small mt-2 text-center">{{ category.name }}</p>
                  </div>

                  <div v-if="selectedCategoryId === category.id" class="subcategory-list mt-2">
                    <div class="collapse show">
                      <ul class="list-unstyled">
                        <li v-for="subcategory in category.subcategories" :key="subcategory.id" @click="selectedSubcategory(subcategory.id)">
                          <a href="#" class="subcategory-link">{{ subcategory.name }}</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!--Cierre card-body-->
        </div> <!--Cierre card-->

        <!--Ofertas de trabajo-->
        <h3 class="mt-4">Ofertas disponibles</h3>
        <div class="row">
          <div v-for="offer in offers" :key="offer.id" class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{ offer.title }}</h5>
                <p class="card-text">{{ offer.description }}</p>
                <p><strong>Ubicación:</strong> {{ offer.location }}</p>
                <button class="btn btn-primary apply" @click="apply(offer.id)">Postularse</button>
              </div>
            </div>
          </div>
        </div>

        <!--Ofertas-->
        <h3 class="mt-5">Mis ofertas postuladas</h3>
        <div class="card mx-auto position-relative" style="max-width: 600px;"></div>
        
        <div v-if="appliedOffers.length > 0" class="position-relative" style="min-height: 200px;">
          <span>{{ currentOfferIndex +1 }} de {{ appliedOffers.length }}</span>
          <!--Botón izquierda-->
          <button 
            v-if="appliedOffers.length > 1"
            class="btn btn-secondary prevOffer position-absolute top-50 start-0 translate-middle-y"
            @click="prevOffer"
            :disabled="currentOfferIndex === 0">
            <font-awesome-icon :icon="['fas', 'chevron-left']" />
          </button>
          
          <!--Card de la oferta-->
          <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
              <h5 class="card-title">{{ appliedOffers[currentOfferIndex].title }}</h5>
              <p class="card-text">{{ appliedOffers[currentOfferIndex].description }}</p>
              <p><strong>Ubicación: </strong>{{ appliedOffers[currentOfferIndex].location }}</p>
              <button
                class="btn btn-danger position-absolute top-0 end-0 m-2"
                @click="removeApplication(appliedOffers[currentOfferIndex].id)">
                <font-awesome-icon :icon="['fas', 'trash']" />
              </button>
            </div>
          </div>
          
          <!--Botón derecha-->
          <button
            v-if="appliedOffers.length > 1"
            class="btn btn-secondary position-absolute top-50 end-0 translate-middle-y"
            @click="nextOffer"
            :disabled="currentOfferIndex === appliedOffers.length - 1">
            <font-awesome-icon :icon="['fas', 'chevron-right']" />
          </button>
        </div>

      </div>
    </div><!--Cierre row-->

  </div> <!--Cierre container-->


      <!-- Barra lateral derecha: Cursos y mensajes 
      <div class="col-md-3">
        <h3>Cursos disponibles</h3>
        <div class="list-group">
          <div class="list-group-item"> <!vfor cuando tengas el código
            <h5>Nombre del curso</h5>
            <p>Descripción del curso</p>
            <a href="#" class="btn btn-info btn-sm">Ver detalles</a>
          </div>
        </div>

        <h3 class="mt-4">Mensajes</h3>
        <div class="list-group">
          <div class="list-group-item"> <!vfor cuando tengas el código
            <p>Texto del mensaje</p>
          </div>
        </div>
      </div>
    </div>
  </div>
-->
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
