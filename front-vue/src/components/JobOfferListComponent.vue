<script setup>
import { onMounted, ref } from 'vue';
import jobOfferService from '@/services/jobOfferService';

//Estado reactivo:
const offers = ref([]); //para las ofertas
//const isCompany = ref(false); //Para verificar si es una empresa autenticada
//const currentUser = ref(null); //usuario actual, que se extrae del token
const hovered = ref(null); //detectar el hover sobre la oferta
const editedOfferId = ref(null); //controlar qué oferta está siendo editada
const offerToDelete = ref(null);

//Haces un fecth que se ejectura al montar el componente y objtiene las ofertas
const fetchOffers = async () =>{
    try {
        offers.value = await jobOfferService.getAll();
    } catch (error) {
        console.log('Error al cargar las ofertas', error);
    }
}

//Ejecutar el fetch al monatr el componente
onMounted(()=>{
    fetchOffers();
});

//Editar oferta:
const editOffer = (offerId) => {
    editedOfferId.value = offerId; //activar edición para la oferta seleccionada
};

//guardar cambios de la oferta editada
const saveEdit = async (offer) => {
    try {
        await jobOfferService.update(offer);
        editedOfferId.value = null; //finalizar la edición
    } catch (error) {
        console.log('Error al guardar la oferta', error);
    }
}; 

//cancelar edición
const cancelEdit = () => {
    editedOfferId.value = null;
};

//Preparar eliminación de oferta
const prepareDelete = (id) => {
    offerToDelete.value = id;
};

//eliminar oferta
const deleteOffer = async (id) => {
    if(offerToDelete.value !== null){
        try {
            await jobOfferService.delete(id);
            offers.value = offers.value.filter(o => o.id !== id);
            offerToDelete.value = null;
        } catch (error) {
            console.log('Error al eliminar la oferta: ', error);
        }
    }
};

//cancelar eliminar
const cancelDelete = () => {
    offerToDelete.value = null;
};


</script>

<template>
   <div class="container my-4">
      <h2 class="mb-4">Ofertas de empleo</h2>

        <div v-if="offers.length === 0" class="alert alert-info">No hay ofertas disponibles.</div>

        <div class="list-group">
            <div
               v-for="offer in offers"
               :key="offer.id"
               class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row"
            >

            <div class="me-3">
                <div v-if="editedOfferId === offer.id">
                    <!--Formulario para editar inline-->
                    <input v-model="offer.title" />
                    <textarea v-model="offer.description"></textarea>
                    <input v-model="offer.loation" />
                    <input v-model="offer.salary" />

                    <button @click="saveEdit(offer)">Guardar</button>
                    <button @click="cancelEdit()">Cancelar</button>
                </div>
                <div v-else>
                    <!--Vista normal de la oferta-->
                    <h5 @mouseover="hovered = offer.id" 
                        @mouseleave="hovered = null"
                    >
                        {{ offer.title }}

                        <!--Mostrar icono para editar solo si el rartón está sobre la oferta-->
                        <i
                          v-if="hovered === offer.id"
                          class="fas fa-pen"
                          @click="editOffer(offer.id)"
                        ></i>
                    </h5>
                   <!--- <p class="mb-1"><strong>Empresa: </strong>{{ offer.company.name }}</p> -->
                    <p class="mb-1">{{ offer.description }}</p>
                    <p class="mb-1">{{ offer.loation }}</p>
                    <p class="mb-1">{{ offer.salary }}</p>
                    <p class="mb-1">{{ offer.createdAt }}</p>
                </div>
            </div>

                <button class="btn btn-sm btn-outline-danger" @click="prepareDelete(offer.id)">Eliminar</button>

                <!--Confirmar eliminación-->
                <div v-if="offerToDelete === offer.id" class="mt-2">
                    <span>¿Estás seguro de que quieres eliminar esta oferta?</span>
                    <button @click="deleteOffer(offerToDelete)" class="btn btn-sm btn-outline-danger">Eliminar</button>
                    <button @click="cancelDelete" class="btn btn-sm btn-outline-secondary">Cancelar</button>
                </div>
            </div>
            
            </div>
        </div>

</template>

