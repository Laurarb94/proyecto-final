<script setup>
import { ref, onMounted } from 'vue'
import AppliedOffersComponent from '@/components/AppliedOffersComponent.vue'
import UserCoursesComponent from '@/components/UserCoursesComponent.vue'
import applicationService from '@/services/applicationService'
import courseService from '@/services/courseService'
import Swal from 'sweetalert2'


// Estado reactivo
const appliedOffers = ref([])
const enrrolledCourses = ref([])
const currentOfferIndex = ref(0)
const currentCourseIndex = ref(0)
const userId = ref(null)


// Métodos
function prevOffer() {
  if (currentOfferIndex.value > 0) currentOfferIndex.value--
}

function nextOffer() {
  if (currentOfferIndex.value < appliedOffers.value.length - 1) currentOfferIndex.value++
}

function prevCourse() {
  if (currentCourseIndex.value > 0) currentCourseIndex.value--
}

function nextCourse() {
  if (currentCourseIndex.value < enrrolledCourses.value.length - 1) currentCourseIndex.value++
}

async function removeApplication(offerId) {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Quieres cancelar tu postulación a esta oferta?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No, mantener',
    reverseButtons: true
  })

  if (result.isConfirmed) {
    appliedOffers.value = appliedOffers.value.filter(o => o.id !== offerId)
    localStorage.setItem('appliedOffers', JSON.stringify(appliedOffers.value))
    if (currentOfferIndex.value >= appliedOffers.value.length) {
      currentOfferIndex.value = Math.max(appliedOffers.value.length - 1, 0)
    }

    Swal.fire(
      'Postulación cancelada!',
      'Te has desinscrito de la oferta correctamente',
      'success'
    )
  }
}

// Carga inicial
onMounted(async () => {
  userId.value = parseInt(localStorage.getItem('userId'))

  try {
    const response = await applicationService.getUserApplications(userId.value)
    appliedOffers.value = response.data || []
    currentOfferIndex.value = appliedOffers.value.length > 0 ? 0 : -1
    console.log('Ofertas aplicadas:', appliedOffers.value)
  } catch (error) {
    console.error('Error al obtener las aplicaciones:', error)
  }

  try {
    const response = await courseService.getUserCourses(userId.value)
    enrrolledCourses.value = response.data || []
  } catch (error) {
    console.error('Error al obtener los cursos:', error)
  }
})

</script>

<template>

<div class="container">
   <h2 class="title">Mis ofertas de empleo</h2>
   <AppliedOffersComponent
       :appliedOffers = "appliedOffers"
       :currentOfferIndex = "currentOfferIndex"
       @prev-offer = "prevOffer"
       @next-offer = "nextOffer"
       @remove-application = "removeApplication"
   />

   <h2 class="title mt-5">Mis cursos</h2>
   <UserCoursesComponent
      :courses="enrrolledCourses"
      :currentCourseIndex="currentCourseIndex"
      @prev-course="prevCourse"
      @next-course="nextCourse"
   />
</div>

</template>

<style scoped>
.container{
  margin-top: 20px;
}

.title{
  margin-top: 10px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: #333;
}



</style>