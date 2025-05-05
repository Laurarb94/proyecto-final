<script>
export default {
  props: {
    appliedOffers: Array,
    currentOfferIndex: Number
  },

  created(){
    console.log('Ofertas aplicadas: ', this.appliedOffers);
  },
  
  methods: {
    prevOffer() {
      this.$emit('prev-offer');
    },
    nextOffer() {
      this.$emit('next-offer');
    },
    removeApplication(offerId) {
      this.$emit('remove-application', offerId);
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
    <div class="d-flex justify-content-center align-items-center gap-0">

        <!--Botón anterior-->
        <button
            v-if="appliedOffers.length > 1"
            class="btn btn-secondary"
            @click="prevOffer"
            :disabled="currentOfferIndex === 0">
            <font-awesome-icon :icon="['fas', 'chevron-left']" />
        </button>

        <!--card de la oferta-->
        <div class="card w-75 mx-auto" style="max-width: 600px;">
            <div v-if="appliedOffers[currentOfferIndex]" class="card-body position-relative">
            <h5 class="card-title">{{ appliedOffers[currentOfferIndex].title }}</h5>
            <p class="card-text">{{ appliedOffers[currentOfferIndex].description }}</p>
            <p><strong>Ubicación:</strong> {{ appliedOffers[currentOfferIndex].location }}</p>

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
</div>

<!--Mensjae si aún no hay ofertas-->
<div v-else class="text-center py-4">
    <p class="text-muted">Todavía no tienes ninguna postulación activa</p>
</div>

</template>