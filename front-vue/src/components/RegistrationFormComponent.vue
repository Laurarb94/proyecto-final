<script setup>
import { registerUser } from '@/services/userService';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

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
    biography:''
});

//Obtienes el router:
const router = useRouter();

//Función para registrar a un usuario
const handleRegister = async() => {
    try{
      const response = await registerUser(user.value);
      console.log("Respuesta completa del backend: ", response);
      alert(response.message || "Usuario registrado con éxito");

      router.push({ name: 'dashboardUser'}); //Redirigir al usuario después del registro a la página privada
    }catch(error){
      alert(error.response?.data.error || 'Error en el registro');
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
        biography:''
    };
};


</script>

<template>
    <div class="container mt-5">
        <h2>Formulario de registro</h2>
        <form @submit.prevent="handleRegister">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input v-model="user.name" type="text" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="last_name1" class="form-label">Primer apellido</label>
                <input v-model="user.last_name1" type="text" id="last_name1" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="last_name2" class="form-label">Segundo apellido</label>
                <input v-model="user.last_name2" type="text" id="last_name2" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input v-model="user.email" type="email" id="email" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input v-model="user.password" type="password" id="password" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">País</label>
                <input v-model="user.country" type="text" id="country" class="form-control" required/>
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">Ciudad</label>
                <input v-model="user.city" type="text" id="city" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Teléfono</label>
                <input v-model="user.phone" type="number" id="phone" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Foto</label>
                <input v-model="user.photo" type="text" id="photo" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="biography" class="form-label">Biografía</label>
                <textarea v-model="user.biography" type="text" id="biography" class="form-control" rows="10"></textarea>
            </div>

            <button type="button" class="btn btn-secondary" @click="cancelEdit">Cancelar registro</button>
            <button type="submit" class="btn btn-primary">Registrase</button>

        </form>
    </div>

</template>
