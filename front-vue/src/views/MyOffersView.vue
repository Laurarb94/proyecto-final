<script setup>
import { ref, onMounted } from 'vue'
import AppliedOffersComponent from '@/components/AppliedOffersComponent.vue'
import UserCoursesComponent from '@/components/UserCoursesComponent.vue'
import applicationService from '@/services/applicationService'
import courseService from '@/services/courseService'
import Swal from 'sweetalert2'
import { off } from 'process'


// Estado reactivo
const appliedOffers = ref([])
const currentOfferIndex = ref(0)
const userId = ref(null)


// Métodos
function prevOffer() {
  if (currentOfferIndex.value > 0) currentOfferIndex.value--
}

function nextOffer() {
  if (currentOfferIndex.value < appliedOffers.value.length - 1) currentOfferIndex.value++
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
    await applicationService.deleteApplication(userId.value, offerId)

    const response = await applicationService.getUserApplications(userId.value)
    console.log('Postulaciones después de eliminar: ', response.data)

    appliedOffers.value = response.data || []
    currentOfferIndex.value = appliedOffers.value.length > 0 ? 0 : -1

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