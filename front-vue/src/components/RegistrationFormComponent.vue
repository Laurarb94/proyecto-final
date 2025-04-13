<script setup>
import { registerUser } from '@/services/userService';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

//Definir al usuario como un objeto reactivo
const user = ref({
    name: '',
    last_name1:'',
    last_name2:'',
    email:'',
    password:'',
    country:'',
    city:'',
    phone:'',
    photo:'',
    biography:'',
    cv:''
});

//Estado para controlar los pasos:
const step = ref(1); //inicias en el paso 1

//Obtienes el router:
const router = useRouter();

//Función para pasar al siguiente paso del form:
const nextStep = () => {
    if (step.value < 3){
        step.value++;
    }
};

//Función para volver al paso anterior
const prevStep = () => {
    if(step.value > 1){
        step.value--;
    }
};

//Registrar al usuario
const handleRegister = async() => {
    try {
        const formData = new FormData();
        formData.append('name', user.value.name);
        formData.append('last_name1', user.value.last_name1);
        formData.append('last_name2', user.value.last_name2);
        formData.append('email', user.value.email);
        formData.append('password', user.value.password);
        formData.append('phone', user.value.phone);
        formData.append('country', user.value.country);
        formData.append('city', user.value.city);
        formData.append('biography', user.value.biography);

        // Adjuntar los archivos
        if (user.value.photo) {
            formData.append('photo', user.value.photo);
        }
        if (user.value.cv) {
            formData.append('cv', user.value.cv);
        }

        const response = await registerUser(formData);
        console.log("Respuesta completa del backend: ", response);

        localStorage.setItem('userId', response.user.id);

        await Swal.fire({
            title: '¡Registro exitoso!',
            text: 'Tu cuenta ha sido creada con éxito',
            icon: 'success',
            confirmButtonText: 'Ir a tu página privada',
            background: '#f8f9fa',
            customClass: {
                popup: 'rounded-4 shadow-lg',
                title: 'fw-bold',
                confirmButton: 'btn btn-primary'
            }
        });

        router.push({ name: 'dashboardUser'}); // Redirigir después del registro
    } catch (error) {
        Swal.fire({
            title: 'Error en el registro',
            text: error.response?.data?.error || 'Ha ocurrido un error inesperado',
            icon: 'error',
            confirmButtonText: 'Intentar de nuevo'
        });
    }
};

//Función para cancelar el registro
const cancelEdit = () => {
    user.value = {
        name: '',
        last_name1:'',
        last_name2:'',
        email:'',
        password:'',
        country:'',
        city:'',
        phone:'',
        photo:'',
        biography:'',
        cv:''
    };
    step.value = 1; //volver al paso 1
};

//Función para manejar el cambio de archivos
const handleFileChange = (field, event) =>{
    const file = event.target.files[0];
    if(file){
        user.value[field] = file;
    }
};

</script>

<template>
      <div class="container mt-5">
    <h2>Formulario de Registro</h2>

    <!-- PAso 1 -->
    <div v-if="step === 1" class="card shadow-sm mb-4">
      <div class="card-header">
        <h4>Datos de Cuenta</h4>
      </div>
      <div class="card-body">
        <form @submit.prevent="nextStep">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input v-model="user.email" type="email" id="email" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input v-model="user.password" type="password" id="password" class="form-control" required />
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" @click="cancelEdit">Cancelar</button>
            <button type="submit" class="btn btn-primary">Siguiente</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Paso 2 -->
    <div v-if="step === 2" class="card shadow-sm mb-4">
      <div class="card-header">
        <h4>Datos Personales</h4>
      </div>
      <div class="card-body">
        <form @submit.prevent="nextStep">
          <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input v-model="user.name" type="text" id="name" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="last_name1" class="form-label">Primer Apellido</label>
            <input v-model="user.last_name1" type="text" id="last_name1" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="last_name2" class="form-label">Segundo Apellido</label>
            <input v-model="user.last_name2" type="text" id="last_name2" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input v-model="user.phone" type="number" id="phone" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="country" class="form-label">País</label>
            <input v-model="user.country" type="text" id="country" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="city" class="form-label">Ciudad</label>
            <input v-model="user.city" type="text" id="city" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="biography" class="form-label">Biografía</label>
            <textarea v-model="user.biography" type="text" id="biography" class="form-control" rows="3"></textarea>
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" @click="prevStep">Volver</button>
            <button type="submit" class="btn btn-primary">Siguiente</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Paso 3-->
    <div v-if="step === 3" class="card shadow-sm mb-4">
      <div class="card-header">
        <h4>Subir Foto y CV</h4>
      </div>
      <div class="card-body">
        <form @submit.prevent="handleRegister">
          <div class="mb-3">
            <label for="photo" class="form-label">Foto de Perfil</label>
            <input type="file" id="photo" class="form-control" @change="handleFileChange('photo', $event)" />
          </div>

          <div class="mb-3">
            <label for="cv" class="form-label">Currículum Vitae (PDF)</label>
            <input type="file" id="cv" class="form-control" @change="handleFileChange('cv', $event)" />
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" @click="prevStep">Volver</button>
            <button type="submit" class="btn btn-primary">Registrarse</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  border-radius: 12px;
  transition: all 0.3s ease;
}


.card-header {
  background-color: #f7f7f7;
  border-bottom: 2px solid #ddd;
  text-align: center;
  font-weight: bold;
  padding: 15px;
}

.card-body {
  padding: 20px;
}

button {
  width: 48%;
}

button:focus {
  outline: none;
}

form {
  padding: 10px;
}
</style>
