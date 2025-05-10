<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  offers: {
    type: Array, 
    required: true
  }
})

const emit = defineEmits(['apply-offer'])

function applyToOffer(offerId) {
  emit('apply-offer', offerId)
}


</script>

<template>
    <div class="row">
        <div v-for="offer in offers" :key="offer.id" class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ offer.title }}</h5>
                    <p class="card-text">{{ offer.description }}</p>
                    <p><strong>Ubicación:</strong> {{ offer.location }}</p>
                    <button class="btn btn-primary apply" @click="applyToOffer(offer.id)">Postularse</button>
                </div>
            </div>
        </div>
    </div>
</template>