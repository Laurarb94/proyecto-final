<script setup>
import { defineProps, defineEmits } from 'vue'
import { useRouter } from 'vue-router'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const props = defineProps({
  appliedOffers: {
    type: Array,
    required: true
  },
  currentOfferIndex: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['prev-offer', 'next-offer', 'remove-application'])

const router = useRouter()

function prevOffer() {
  if (props.currentOfferIndex > 0) {
    emit('prev-offer')
  }
}

function nextOffer() {
  if (props.currentOfferIndex < props.appliedOffers.length - 1) {
    emit('next-offer')
  }
}

function removeApplication(offerId) {
  emit('remove-application', offerId)
}

function goBack() {
  router.go(-1)
}

</script>

<template>

<div v-if="appliedOffers.length > 0" class="position-relative py-4">

    <!--Contador-->
    <div class="custom-counter">
      {{ currentOfferIndex +1 }} de {{ appliedOffers.length }}
    </div>

    <!--Navegación y card-->
    <div class="offers-nav-container">

        <!--Botón anterior-->
        <button
            v-if="appliedOffers.length > 1"
            class="btn btn-secondary"
            @click="prevOffer"
            :disabled="currentOfferIndex === 0">
            <font-awesome-icon :icon="['fas', 'chevron-left']" />
        </button>

        <!--card de la oferta-->
        <div class="offer-card w-75 mx-auto">
            <div v-if="appliedOffers[currentOfferIndex]" 
                :key="appliedOffers[currentOfferIndex].id"
                class="card-body position-relative">
            <h5 class="card-title">{{ appliedOffers[currentOfferIndex].jobOffer.title }}</h5>
            <p class="card-text">{{ appliedOffers[currentOfferIndex].jobOffer.description }}</p>
            <p class="card-text">Salario: {{ appliedOffers[currentOfferIndex].jobOffer.salary }}</p>
            <p><strong>Ubicación:</strong> {{ appliedOffers[currentOfferIndex].jobOffer.loation }}</p>

            <!--Botón eliminar oferta-->
            <button
                class="btn btn-danger position-absolute top-0 end-0 m-2"
                @click="removeApplication(appliedOffers[currentOfferIndex].id)">
                <font-awesome-icon :icon="['fas', 'trash']" />
            </button>
        </div>
    </div>
    
    <!--Botón siguiente-->
    <button
        v-if="appliedOffers.length > 1"
        class="btn btn-outline-secondary"
        @click="nextOffer"
        :disabled="currentOfferIndex === appliedOffers.length -1">
        <font-awesome-icon :icon="['fas', 'chevron-right']" />
    </button>
    </div>

    <div class="card-footer text-center mt-4">
      <button @click="goBack" class="btn btn-secondary">Volver</button>
    </div>

</div>

<!--Mensjae si aún no hay ofertas-->
<div v-else class="text-center py-4">
    <p class="text-muted">Todavía no tienes ninguna postulación activa</p>
</div>

</template>

<style scoped>
.offers-container{
  display: flex;
  flex-direction: column;
  align-items: center;
}

.offers-nav-container{
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

.offer-card{
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

.offer-card:hover{
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  transform: translateY(-2px);
}

.offer-card h5{
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 10px;
  color: #6c63ff;
}

.offer-card .card-text{
  font-size: 1rem;
  margin-bottom: 10px;
  color: #555;
}

.offer-card .card-body {
  padding-right: 50px; /* deja espacio para el botón */
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

/* Papelera */
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

/* Botón "Volver" */
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