<script>
export default {
  props: {
    appliedOffers: Array,
    currentOfferIndex: Number
  },

  methods: {
    prevOffer() {
      if(this.currentOfferIndex > 0){
        this.$emit('prev-offer');
      }
    },
    nextOffer() {
      if(this.currentOfferIndex < this.appliedOffers.length -1){
        this.$emit('next-offer');
      }
    },
    removeApplication(offerId) {
      this.$emit('remove-application', offerId);
    },
    goBack() {
      this.$router.go(-1); 
    }
  }
};
</script>

<template>

<div v-if="appliedOffers.length > 0" class="position-relative py-4">

    <!--Contador-->
    <div class="text-center mb-3">
        <span class="badge bg-primary fs-6">{{ currentOfferIndex +1 }} de {{ appliedOffers.length }}</span>
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

.offer-card{
  max-width: 600px;
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  background-color: white;
  margin-top: 10px;
  margin-bottom: 20px;
}

.offer-card h5{
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 10px;
}

.offer-card .card-text{
  font-size: 1rem;
  margin-bottom: 8px;
}

button{
  border-radius: 50%;
  width: 35px;
  height: 35px;
  display: flex;
  justify-content: center;
  align-items: center;
}

button:disabled{
  opacity: 0.5;
}

button.btn-danger{
  background-color: #f44336;
}

button.btn-outline-secondary{
  background-color: #f1f1f1;
  color: #333;
  border: 1px solid #ccc;
}

.card-footer .btn{
  background-color: #007bff;
  color: #fff; 
  padding: 14px 28px; 
  font-size: 1rem; 
  border-radius: 30px; 
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
  transition: all 0.3s ease-in-out; 
  border: none; 
}

.card-footer .btn:hover {
  background-color: #0056b3; 
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); 
}

.card-footer .btn:focus {
  outline: none; 
  box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.5); 
}


</style>