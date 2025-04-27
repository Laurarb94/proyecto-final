<script setup>
import { onMounted, ref, resolveDirective } from 'vue';
import jobOfferService from '@/services/jobOfferService';
import { useRoute } from 'vue-router';
import { off } from 'process';

//Estado reactivo
const offers = ref([]);
const newOffer = ref({
    title: '',
    companyName: '',
    description: '',
    loation: '',
    salary: '',
    createdAt: ''
});
const currentOffer = ref ({
    title: '',
    companyName: '',
    description: '',
    loation: '',
    salary: '',
    createdAt: ''
}); //guardar oferta actual que se está editando, inicializarlo como objeto vacío
const isCreating = ref(false);
const isEditing = ref(false);

const fetchOffers = async () => {
    try {
        offers.value = await jobOfferService.getAll();
    } catch (error) {
        console.log('Error al cargar las ofertas', error);
    }
};

onMounted(()=>{
    fetchOffers();
});

//abrir o cerrar el form
const toggleCreateForm = () => {
    isCreating.value = !isCreating.value;
};

//Abrir form de edición
const editOffer = (offer) => {
    currentOffer.value = { ...offer}; //clonar oferta actual para editar
    isEditing.value = true; //activar modo edición
};

//guardar oferta editada:
const saveEdit = async () => {
    try {
        await jobOfferService.update(currentOffer.value); // Llamar a la API para actualizar
        const index = offers.value.findIndex(offer => offer.id === currentOffer.value.id);
        if (index !== -1) {
            offers.value[index] = { ...currentOffer.value }; // Actualizar la oferta en la lista
        }
        isEditing.value = false; // Salir del modo edición
        currentOffer.value = null; // Limpiar la oferta actual
    } catch (error) {
        console.log('Error al guardar la oferta', error);
    }
};

// Cancelar la edición y restablecer el formulario
const cancelEdit = () => {
    isEditing.value = false;
    currentOffer.value = null;
};

//Restablecer el form de creación
const resetForm = () => {
    newOffer.value = {
        title: '',
        companyName: '',
        description: '',
        loation: '',
        salary: '',
        createdAt: ''
    };
};

//crear oferta
const createOffer = async() => {
    try {
        const offerData = { ...newOffer.value }; // newOffer.value para acceder a los datos del formulario
        const response = await jobOfferService.create(offerData);
        console.log('Oferta creada exitosamente', response);
        offers.value.push(response); // Agregamos la nueva oferta a la lista
        resetForm(); // Restablecer el formulario
        isCreating.value = false; // Cerrar el formulario
    } catch (error) {
        console.error('Error al crear la oferta', error.response ? error.response.data : error);
    }
};

</script>

<template>
  <div class="container my-4">
    <h2 class="mb-4">Ofertas de empleo</h2>

    <!-- Botón para abrir el formulario de crear oferta -->
    <button @click="toggleCreateForm" class="btn btn-primary mb-3">Crear oferta</button>

    <!-- Formulario para crear una nueva oferta -->
    <div v-if="isCreating" class="mb-4">
      <h3>Crear nueva oferta</h3>
      <form @submit.prevent="createOffer">
        <div class="mb-3">
          <label for="title" class="form-label">Título de la Oferta</label>
          <input v-model="newOffer.title" type="text" class="form-control" id="title" placeholder="Título de la oferta" required />
        </div>
        <div class="mb-3">
          <label for="companyName" class="form-label">Nombre de la empresa</label>
          <input v-model="newOffer.companyName" type="text" class="form-control" id="companyName" placeholder="Empresa" required />
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Descripción de la oferta</label>
          <textarea v-model="newOffer.description" class="form-control" id="description" placeholder="Descripción" required></textarea>
        </div>
        <div class="mb-3">
          <label for="salary" class="form-label">Salario</label>
          <input v-model="newOffer.salary" type="text" class="form-control" id="salary" placeholder="Salario" required />
        </div>
        <div class="mb-3">
          <label for="loation" class="form-label">Ubicación</label>
          <input v-model="newOffer.loation" type="text" class="form-control" id="loation" placeholder="Ubicación" required />
        </div>
        <div class="mb-3">
          <label for="createdAt" class="form-label">Fecha de creación</label>
          <input v-model="newOffer.createdAt" type="date" class="form-control" id="createdAt" required />
        </div>
        <button type="submit" class="btn btn-success">Crear oferta</button>
        <button type="button" @click="resetForm" class="btn btn-secondary ms-2">Cancelar</button>
      </form>
    </div>

    <!-- Formulario de edición de oferta -->
    <div v-if="isEditing" class="mb-4">
      <h3>Editar oferta</h3>
      <form @submit.prevent="saveEdit">
        <div class="mb-3">
          <label for="editTitle" class="form-label">Título de la Oferta</label>
          <input v-model="currentOffer.title" type="text" class="form-control" id="editTitle" placeholder="Título de la oferta" required />
        </div>
        <div class="mb-3">
          <label for="editCompanyName" class="form-label">Nombre de la empresa</label>
          <input v-model="currentOffer.companyName" type="text" class="form-control" id="editCompanyName" placeholder="Empresa" required />
        </div>
        <div class="mb-3">
          <label for="editDescription" class="form-label">Descripción de la oferta</label>
          <textarea v-model="currentOffer.description" class="form-control" id="editDescription" placeholder="Descripción" required></textarea>
        </div>
        <div class="mb-3">
          <label for="editSalary" class="form-label">Salario</label>
          <input v-model="currentOffer.salary" type="text" class="form-control" id="editSalary" placeholder="Salario" required />
        </div>
        <div class="mb-3">
          <label for="editLoation" class="form-label">Ubicación</label>
          <input v-model="currentOffer.loation" type="text" class="form-control" id="editLoation" placeholder="Ubicación" required />
        </div>
        <div class="mb-3">
          <label for="editCreatedAt" class="form-label">Fecha de creación</label>
          <input v-model="currentOffer.createdAt" type="date" class="form-control" id="editCreatedAt" required />
        </div>
        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <button type="button" @click="cancelEdit" class="btn btn-secondary ms-2">Cancelar</button>
      </form>
    </div>

    <!-- Lista de ofertas -->
    <div v-if="offers.length === 0" class="alert alert-info">No hay ofertas disponibles</div>
    <div class="list-group">
      <div
        v-for="offer in offers"
        :key="offer.id"
        class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row"
      >
        <div>
          <h5>{{ offer.title }}</h5>
          <p class="mb-1"><strong>Empresa: </strong>{{ offer.companyName }}</p>
          <p class="mb-1">{{ offer.description }}</p>
          <p class="mb-1">{{ offer.salary }}</p>
          <p class="mb-1">{{ offer.createdAt }}</p>
          <button @click="editOffer(offer)" class="btn btn-sm btn-outline-primary">Editar</button>
        </div>
      </div>
    </div>
  </div>
</template>