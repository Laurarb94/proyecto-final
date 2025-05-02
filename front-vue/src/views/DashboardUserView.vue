<script>
import { logoutUser } from '@/services/authService';
import { getUserById } from '@/services/userService';
import jobOfferService from '@/services/jobOfferService';
import categoryService from '@/services/categoryService';
import applicationService from '@/services/applicationService';
import Swal from 'sweetalert2';

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
          //console.log("usuario cargado: ", this.user);
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
        
        response.forEach(offer => {
          console.log('Oferta ID: ', offer.id);
          //console.log('Claves de la oferta: ', Object.keys(offer));
        });

        this.offers = response.filter(offer =>
          offer.subcategory && offer.subcategory.id === parseInt(this.selectedSubcategoryId)
        );

        //console.log('Ofertas filtradas: ', this.offers);

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

    getCategoryIconKey(categoryName){
      const icon = this.categoryIcons[categoryName] || 'fa-question-circle'; //si no encuentra la categoría devuelve un icono por defecto
      return icon;
    },

    removeApplication(offerId){
      this.appliedOffers = this.appliedOffers.filter(o => o.id !== offerId);
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
            <a v-if="user.cv" :href= "`http://localhost:8000/uploads/cvs/${user.cv}`" class="btn btn-primary" target="_blank">Ver CV</a>
          </div>
        </div>
      </div>

      <!-- Columna de ofertas -->
      <div class="col-md-8">
        <!-- Filtro de categorías con iconos -->
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

        <!-- Ofertas de trabajo -->
        <h3 class="mt-4">Ofertas disponibles</h3>
        <div class="row">
          <div v-for="offer in offers" :key="offer.id" class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{ offer.title }}</h5>
                <p class="card-text">{{ offer.description }}</p>
                <p><strong>Ubicación:</strong> {{ offer.location }}</p>
                <button class="btn btn-primary" @click="apply(offer.id)">Postularse</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Ofertas postuladas -->
        <h3 class="mt-5">Mis ofertas postuladas</h3>
        <div class="row">
          <div v-for="offer in appliedOffers" :key="offer.id" class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{ offer.title }}</h5>
                <p class="card-text">{{ offer.description }}</p>
                <p><strong>Ubicación:</strong> {{ offer.location }}</p>
                <button class="btn btn-danger" @click="removeApplication(offer.id)">Eliminar Postulación</button>
              </div>
            </div>
          </div>
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
  margin-bottom: 20px;
}

.card-body {
  padding: 1.5rem;
}

.card-title {
  font-size: 1.25rem;
}

.btn {
  font-size: 0.875rem;
}

.profile-photo {
  height: 200px;
  object-fit: cover;
}

.category-item {
  text-align: center;
  cursor: pointer;
}

.category-item .category-icon {
  font-size: 2rem;
  color: #007bff;
  border: 2px solid #007bff;
  width: 100px;
  height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;
  border-radius: 50%; /* círculo */
}

.category-item .subcategory-list {
  margin-top: 10px;
}

.subcategory-link {
  color: #007bff;
  cursor: pointer;
  text-decoration: none;
  font-weight: bold;
  display: block;
  padding: 8px;
  transition: all 0.3s ease;
}

.subcategory-link:hover {
  text-decoration: underline;
  background-color: #f0f8ff;
}

.category-item .category-link:hover {
  opacity: 0.7;
}

.subcategory-link:active {
  color: #0056b3;
}

</style>
