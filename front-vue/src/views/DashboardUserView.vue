<script>
import { logoutUser } from '@/services/authService';
import { getUserById } from '@/services/userService';
import { useAuth } from '@/composables/useAuth';
import jobOfferService from '@/services/jobOfferService';
import categoryService from '@/services/categoryService';
import UserComponent from '@/components/UserComponent.vue';
import UserCoursesComponent from "@/components/UserCoursesComponent.vue";
import UserMessagesComponent from "@/components/UserMessagesComponent.vue";
import ProfileCardComponent from '@/components/ProfileCardComponent.vue';
import CategoryFilterComponent from '@/components/CategoryFilterComponent.vue';
import AppliedOffersComponent from '@/components/AppliedOffersComponent.vue';
import applicationService from '@/services/applicationService';
import Swal from 'sweetalert2';
import OfferListComponent from '@/components/OfferListComponent.vue';
import router from '@/router';
import courseService from '@/services/courseService';


export default {
  name: 'DashboardUserView',

  components: {
    UserComponent,
    AppliedOffersComponent,
    CategoryFilterComponent,
    OfferListComponent,
    ProfileCardComponent,
    UserCoursesComponent,
    UserMessagesComponent,
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
      courses: [],
      filteredCourses: [],
      enrrolledCourses: [],
      appliedCourses: [],
      
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
      this.$router.push({ name: "myCourses"});
    },

    // Método para cambiar la vista a 'messages'
    goToMessages() {
      this.$router.push({name: "messages"});
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
      this.selectedSubcategory = subcategory.name || subcategory;
      this.selectedSubcategoryId = subcategory.id;
      console.log('Subcategoría seleccionada: ', this.selectedSubcategory);
      this.fetchFilteredOffers();
      this.fetchFilteredCourses();
    },

    async fetchFilteredOffers(){
      try {
        const response = await jobOfferService.getAll();
        this.filteredOffers = response.filter(offer =>
          offer.subcategory && offer.subcategory.id === this.selectedSubcategoryId
        );
        console.log('Cursos filtrados: ', this.filteredCourses);
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

  async apply(offerId) {
    try {
      const response = await applicationService.applyToOffer(offerId, this.userId);

      // Mover la oferta a "mis ofertas postuladas"
      const appliedOffer = this.offers.find(offer => offer.id === offerId);
      if (appliedOffer) {
        this.appliedOffers.push(appliedOffer);
        this.offers = this.offers.filter(offer => offer.id !== offerId);
        localStorage.setItem('appliedOffers', JSON.stringify(this.appliedOffers));
      }
      
      // Sweet Alert con opciones
      Swal.fire({
        icon: 'success',
        title: '¡Postulación exitosa!',
        text: '¿Qué te gustaría hacer ahora?',
        showCancelButton: true,
        confirmButtonText: 'Ver mis ofertas',
        cancelButtonText: 'Seguir navegando',
        reverseButtons: true
      }).then(result => {
        if (result.isConfirmed) {
          this.$router.push({ name: 'myOffers' });
        }
        // Si pulsa cancelar, no hace nada y se queda en la misma página
        });
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al postularte',
          confirmButtonText: 'Aceptar',
        });
      }
    },

    async handleApplyCourse(courseId) {
  try {
    // Aplica al curso
    const response = await courseService.applyToCourse(courseId, this.userId);
    console.log('Cursos inscritos:', this.appliedCourses);

    if (Array.isArray(this.appliedCourses)) {
      // Mover el curso a los cursos aplicados
      const appliedCourse = this.courses.find(course => course.id === courseId);
      if (appliedCourse) {
        // Añadir a appliedCourses
        this.appliedCourses.push(appliedCourse);
        // Eliminar de la lista de cursos
        this.courses = this.courses.filter(course => course.id !== courseId);
        
        // Si el curso estaba en filteredCourses, eliminarlo también de allí
        this.filteredCourses = this.filteredCourses.filter(course => course.id !== courseId);

        // Actualiza el localStorage con los cursos aplicados
        localStorage.setItem('appliedCourses', JSON.stringify(this.appliedCourses));
      }

      // Sweet Alert con opciones
      Swal.fire({
        icon: 'success',
        title: '¡Inscripción exitosa!',
        text: '¿Qué te gustaría hacer ahora?',
        showCancelButton: true,
        confirmButtonText: 'Ver mis cursos',
        cancelButtonText: 'Seguir explorando',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          this.$router.push({ name: 'myCourses' });
        }
      });

    } else {
      console.error('appliedCourses no es un array:', this.appliedCourses);
    }
  } catch (error) {
    console.log(error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Hubo un problema al inscribirte',
      confirmButtonText: 'Aceptar',
    });
  }
},



    async handleRemoveCourse(courseId) {
        try {
            const userId = this.userId;
            const response = await courseService.removeFromCourse(userId, courseId); // Llamada para eliminar curso
            console.log('Respuesta al eliminar curso:', response);

            // Mensaje de éxito
            Swal.fire(
                '¡Eliminado!',
                'El curso ha sido eliminado de tus inscripciones.',
                'success'
            );

            // Actualiza la lista de cursos o realiza cualquier otra acción
            this.enrrolledCourses = this.enrrolledCourses.filter(course => course.id !== courseId); // Actualiza el listado de cursos
        } catch (error) {
            console.error('Error al eliminar el curso:', error);
            Swal.fire(
                'Error',
                'Hubo un problema al eliminar el curso. Intenta nuevamente.',
                'error'
            );
        }
    },
    
    clearFilter(){
      this.selectedSubcategory = null;
      this.selectedSubcategoryId = null;
      this.filteredOffers = [];
      this.filteredCourses = [];
    },

    async fetchFilteredCourses() {
      try {
        const response = await courseService.getAll();
        this.courses = response;
        const filtered = response.filter(course => course.subcategory === this.selectedSubcategory); // Hacer una copia explícita de los cursos antes de filtrarlos
        this.filteredCourses = [...filtered]; // Asegúrate de que filteredCourses se actualice correctamente
      } catch (error) {
        console.log('Error al filtrar los cursos: ', error);
      }
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


    <div 
        class="row justify-content-center align-items-start fade-in"
        v-for="offer in filteredOffers"
        :key="offer.id">
      
        <!-- Columna de la OFERTA -->
        <div class="col-md-6 d-flex">
          <div class="job-card flex-fill">
            <div class="job-header"> <h5>{{ offer.title }}</h5> </div>
            <p class="job-description">{{ offer.description }}</p>
            <button class="btn btn-outline-primary" @click="handleApplyOffer(offer.id)"> Aplicar </button>
          </div>
        </div>
        
        <!-- Columna del CURSO relacionado -->
        <div class="col-md-6 d-flex">
          <div class="course-card flex-fill">
            <div class="info-course-msg mb-2">
              <strong>¡Mejora tus oportunidades!</strong><br /> Apúntate a este curso para destacarte en esta oferta:
            </div>
            <div v-if="filteredCourses.length > 0">
              <div class="course-header"> <h5>{{ filteredCourses[0].title }}</h5> </div>
              <p class="course-description">{{ filteredCourses[0].content }}</p>
             <button class="btn btn-outline-primary" @click="handleApplyCourse(filteredCourses[0].id)">Apúntate</button>
            </div>
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
.job-card,
.course-card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 20px;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
  min-height: 300px;
}

.flex-fill{
  flex:1;
}

.job-card:hover,
.course-card:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.job-header,
.course-header {
  font-size: 1.2rem;
  color: #6c63ff; /* morado suave */
  margin-bottom: 0.5rem;
}

.job-card h5,
.course-card h5 {
  font-size: 1.2rem;
  font-weight: bold;
  margin: 0;
}

.job-description, 
.course-description {
  font-size: 1rem;
  color: grey;
  margin-bottom: 1rem;
}

.job-card button, 
.course-card button {
  background-color: transparent;
  color: grey;
  border: 1px solid #6c63ff; /* borde morado */
  padding: 8px 20px;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.job-card button:hover, 
.course-card button:hover {
  background-color: #6c63ff;
  color: #fff;
}

.info-course-msg {
  background: linear-gradient(135deg, #f2f4f8, #e6e9f0); /* tonos grises claros azulados */
  color: #3c3f4a; /* gris oscuro suave */
  padding: 16px;
  border-left: 4px solid #6c63ff; /* acento morado */
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 500;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 1rem;
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
