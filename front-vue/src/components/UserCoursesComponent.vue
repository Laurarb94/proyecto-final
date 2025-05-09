<script>
import courseService from '@/services/courseService';
import axios from 'axios';

export default {
   props: {
    courses: Array,
    currentCourseIndex: Number, 
    userId: Number
  },

  data(){
    return{
        localCourses: [], //dato interno para almacenar los cursos
        localUserId: null,
    }
  },

  watch: {
    // Si la prop 'courses' cambia, actualizar 'localCourses'
    courses(newCourses) {
      this.localCourses = newCourses;
    }
  },

  mounted() {
  // Verificar si el `userId` está disponible en el localStorage
  const userIdFromLocalStorage = localStorage.getItem('userId');
  console.log("User ID desde localStorage:", userIdFromLocalStorage);

  // Si restá en el localStorage, lo asignamos a localuserid
  if (userIdFromLocalStorage) {
    this.localUserId = userIdFromLocalStorage;
  } else {
    console.error("No se encontró el userId en localStorage.");
  }

  //usar localUserId en lugar de intentar cambiar el `prop` directamente
  if (!this.localCourses || this.localCourses.length === 0) {
    this.getUserCourses(this.localUserId); // Hacer la solicitud a la API usando el `localUserId`
  }
},

methods: {
  async getUserCourses(userId) {
    try {
      const response = await axios.get(`http://localhost:8000/api/user/${userId}/courses`);
      console.log("Cursos obtenidos:", response.data);
      if (response.data && response.data.length > 0) {
        this.localCourses = response.data;
      } else {
        console.log("No se encontraron cursos.");
      }
    } catch (error) {
      console.error("Error al obtener los cursos:", error);
    }
  }, 

  prevCourse(){
    if(this.currentCourseIndex > 0){
        this.$emit('prev-course');
    }
  },

  nextCourse(){
    if(this.currentCourseIndex < this.getUserCourses.length -1){
        this.$emit('next-offer');
    }
  },

  removeCouse(courseId){
    this.$emit('remove-application', courseId);
  },

  goBack(){
    this.$router.go(-1);
  }


}
};
</script>

<template>
  <div v-if="localCourses && localCourses.length > 0" class="position-relative py-4">

    <!--Contador-->
    <div class="custom-counter">
        {{ currentCourseIndex +1 }} de {{ localCourses.length }}
    </div>

    <!--Navegación y card-->
    <div class="course-nav-container">
        <button
           v-if="localCourses.length > 1"
           class="btn btn-secondary"
           @click="prevCourse"
           :disabled="currentCourseIndex === 0">
            <font-awesome-icon :icon="['fas', 'chevron-left']" />
        </button>

        <!--card del curso-->
        <div class="course-card w.75 mx-auto">
            <div v-if="localCourses[currentCourseIndex]" class="card-body position-relativ">
                <h5 class="card-title">{{ localCourses[currentCourseIndex].title }}</h5>
                <p class="card-text">{{ localCourses[currentCourseIndex].content }}</p>
                <p class="card-text">{{ localCourses[currentCourseIndex].category }}</p>
                <p class="card-text">{{ localCourses[currentCourseIndex].subcategory }}</p>
            </div>

            <!--Botón eliminar curso-->
            <button
               class="btn btn-danger position absolute tio-0 end-0 m-2"
               @click="removeCouse(localCourses[currentCourseIndex].id)">
               <font-awesome-icon :icon="['fas', 'trash']" />
            </button>
        </div>
    </div>

    <!--Botón siguiente-->
    <button 
       v-if="localCourses.length > 1"
       class="btn btn-outline-secondary"
       @click="nextCourse"
       :disabled="currentCourseIndex === getUserCourses.length -1" >
       <font-awesome-icon :icon="['fas', 'chevron-right']" />  
    </button>

    <div class="card-footer text-center mt-4">
        <button @click="goBack" class="btn btn-secondary">Volver</button>
    </div>

</div>

<!--Mensjae si no hay cursos-->
<div v-else class="text-center py-4">
    <p class="text-muted">Todavía no estás inscrito en ningún curso</p>
</div>
    

</template>

<style scoped>
.course-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.course-nav-container{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.custom-counter{
    display: inline-block;
    background-color: #6c63ff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 25px;
    font-weight: 500;
    font-size: 0.95rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    margin-bottom: 1.5rem;
}

.course-card{
    background-color: #ffffff; 
    border-left: 4px solid #6c63ff; 
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 40px 20px 20px 20px;
    margin: 10px auto 20px auto;
    max-width: 600px;
    min-height: 220px;
    transition: box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    text-align: center;
}

.course-card:hover{
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.course-card h5{
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 10px;
    color: #6c63ff;
}

.course-card .card-text{
    font-size: 1rem;
    margin-bottom: 10px;
    color: #555;
}

.course-card .card.body{
    padding-right: 50px;
    position: relative;
}


.btn-outline-secondary,
.btn-secondary {
  background-color: transparent;
  color: #6c63ff;
  border: 1px solid #6c63ff;
  padding: 8px 16px;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-outline-secondary:hover,
.btn-secondary:hover {
  background-color: #6c63ff;
  color: #fff;
}


button.btn-danger {
  background-color: transparent;
  color: #f44336;
  border: 1px solid #f44336;
  transition: all 0.3s ease;
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

button.btn-danger.position-absolute {
  top: 12px;
  right: 12px;
  z-index: 2;
}

button.btn-danger:hover {
  background-color: #f44336;
  color: #fff;
}

.card-footer .btn {
  background-color: transparent;
  color: #6c63ff;
  border: 1px solid #6c63ff;
  padding: 12px 28px;
  font-size: 1rem;
  border-radius: 30px;
  transition: all 0.3s ease-in-out;
}

.card-footer .btn:hover {
  background-color: #6c63ff;
  color: #fff;
}




</style>