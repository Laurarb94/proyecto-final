<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Swal from 'sweetalert2'

const emit = defineEmits(['prev-course', 'next-course', 'remove-application'])

const router = useRouter()
const localCourses = ref([])
const currentIndex = ref(0)
const localUserId = ref(null)

onMounted(() => {
  const storedUserId = window.localStorage.getItem('userId')
  if (storedUserId) {
    localUserId.value = storedUserId
    getUserCourses(localUserId.value)
  } else {
    console.error('No se encontró el userId.')
  }
})

async function getUserCourses(userId) {
  try {
    const response = await axios.get(`http://localhost:8000/api/user/${userId}/courses`)
    if (response.data && response.data.length > 0) {
      localCourses.value = response.data
    } else {
      console.log('No se encontraron cursos.')
    }
  } catch (error) {
    console.error('Error al obtener los cursos:', error)
  }
}

function prevCourse() {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

function nextCourse() {
  if (currentIndex.value < localCourses.value.length - 1) {
    currentIndex.value++
  }
}

async function removeCourse(courseId) {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Quieres eliminar este curso?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    reverseButtons: true
  })

  if (result.isConfirmed) {
    localCourses.value = localCourses.value.filter(course => course.id !== courseId)
    if (currentIndex.value >= localCourses.value.length) {
      currentIndex.value = Math.max(localCourses.value.length - 1, 0)
    }

    await Swal.fire({
      title: 'Eliminado',
      text: 'El curso ha sido eliminado correctamente',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  }
}

function goBack() {
  router.go(-1)
}

</script>

<template>
  <div v-if="localCourses && localCourses.length > 0" class="position-relative py-4">

    <!--Contador-->
    <div class="custom-counter">
        {{ currentIndex +1 }} de {{ localCourses.length }}
    </div>

    <!--Navegación y card-->
    <div class="course-nav-container">
        <button
           v-if="localCourses.length > 1"
           class="btn btn-secondary"
           @click="prevCourse"
           :disabled="currentIndex === 0">
            <font-awesome-icon :icon="['fas', 'chevron-left']" />
        </button>

        <!--card del curso-->
        <div class="course-card w-75 mx-auto">
            <div v-if="localCourses[currentIndex]" class="card-body position-relativ">
                <h5 class="card-title">{{ localCourses[currentIndex].title }}</h5>
                <p class="card-text">{{ localCourses[currentIndex].content }}</p>
                <p class="card-text">{{ localCourses[currentIndex].category }}</p>
                <p class="card-text">{{ localCourses[currentIndex].subcategory }}</p>
            </div>

            <!--Botón eliminar curso-->
            <button
               class="btn btn-danger position-absolute top-0 end-0 m-2"
               @click="removeCourse(localCourses[currentIndex].id)">
               <font-awesome-icon :icon="['fas', 'trash']" />
            </button>
        </div>

            <!--Botón siguiente-->
            <button 
                v-if="localCourses.length > 1"
                class="btn btn-outline-secondary"
                @click="nextCourse"
                :disabled="currentIndex === localCourses.length -1" >
                <font-awesome-icon :icon="['fas', 'chevron-right']" />  
            </button>

    </div>


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