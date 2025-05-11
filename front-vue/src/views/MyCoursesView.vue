<script setup>
import { ref, onMounted } from 'vue'
import Swal from 'sweetalert2'
import courseService from '@/services/courseService'
import UserCoursesComponent from '@/components/UserCoursesComponent.vue'


//Estado reactivo
const enrrolledCourses = ref([]);
const currentCourseIndex = ref(0);
const userId = ref(null);

onMounted(async () => {
  userId.value = parseInt(localStorage.getItem('userId'))
  try {
    const response = await courseService.getUserCourses(userId.value);
    enrrolledCourses.value = response.data || [];
  } catch (error) {
    console.log('Error al obtener los cursos: ', error);
  }
})


// Navegación
function prevCourse() {
  if (currentCourseIndex.value > 0) currentCourseIndex.value--
}

function nextCourse() {
  if (currentCourseIndex.value < enrrolledCourses.value.length - 1) currentCourseIndex.value++
}

// Eliminar curso
async function removeCourse(courseId) {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Quieres cancelar tu inscripción a este curso?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No, mantener',
    reverseButtons: true
  })

  if (result.isConfirmed) {
    try {
      await courseService.removeFromCourse(userId.value, courseId)
      await fetchCourses()

      Swal.fire('Inscripción cancelada', 'Te has desinscrito del curso correctamente', 'success')
    } catch (error) {
      console.error('Error al eliminar en el backend:', error)
      Swal.fire('Error', 'No se pudo cancelar la inscripción', 'error')
    }
  }
}


</script>

<template>
  <div class="container">
    <!-- Título de Mis Cursos -->
    <h2 class="title">Mis cursos</h2>
    <!-- Mostrar los cursos -->
    <UserCoursesComponent
      :courses="enrrolledCourses"
      :currentCourseIndex="currentCourseIndex"
      :userId="userId"
      @prev-course="prevCourse"
      @next-course="nextCourse"
      @remove-course="removeCourse"
    />
  </div>
</template>

<style scoped>
.container {
  margin-top: 20px;
}

.title {
  margin-top: 10px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: #333;
}

.mt-5 {
  margin-top: 50px;
}
</style>