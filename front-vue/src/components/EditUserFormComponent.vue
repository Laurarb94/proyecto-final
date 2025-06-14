<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { editUser, getUserById, createUser } from '@/services/userService';
import DashboardUserView from '@/views/DashboardUserView.vue';

const route = useRoute(); //esto accede al id del usuario desde la url
const router = useRouter(); //para redirigir a otras rutas

const user = ref({ //estado reactivo para almacenar la info del usuario
    name: '',
    last_name1: '',
    last_name2: '',
    email: '',
    password: '',
    country: '', 
    city: '',
    phone: '', 
    photo: '',
    biography: '', 
    roles: '',
});

//Saber si estás creando o editando un usuario
const isEditMode = ref(false);

//Si estás editando, obtener los datos del usuario al montar el componente: 
onMounted(async()=>{
   const userId = route.params.id; //obtienes el id de la url

   if(userId){
    isEditMode.value = true; //si hay userId estás editando

    try{
        const data = await getUserById(userId); //llamas a la api para obtener los datos del usuario
        user.value = data; //asignas los datos obtenidos al objeto user
      }catch(error){
        console.log("Error al obtener el usuario: ", error);
      }

   }
});

//Manejar el envío del fomrulario
const handleSubmit = async() =>{
    try{
        const userData = { ...user.value}; //desempaqueta los datos del usuario.value antes de pasarlo a la función edit

        //Asegurar que el campo roles sea un array para que no te de fallo:
        if(typeof userData.roles === 'string'){
            userData.roles = [userData.roles];
        }

        if(isEditMode.value){
            await editUser(user.value.id, userData); // Si estás editando, llamas a la funcion de editar
        }else{
            await createUser(userData); //si estás creando un usuario, llamas a la función de crear
        }
        router.push({name: 'dashboardUser'}); //Redirigir al listado de usuarios
    }catch(error){
        console.log("Error al editar al usuario: ", error);
    } 
};

//Cancelar edición
const cancelEdit = () =>{
    router.push({name: 'dashboardUser'});
};

</script>

<template>
    <div class="container mt-4">
        
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4> {{ isEditMode ? 'Editar Usuario' : 'Crear usuario' }} </h4>
            </div>

            <div class="card-body">
                
                <form @submit.prevent="handleSubmit" class="bg-light p-4 rounded shadow mx-auto" style="max-width: 50%;">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input v-model="user.name" type="text" id="name" class="form-control" :required="!isEditMode" />
                    </div>

                    <div class="mb-3">
                        <label for="last_name1" class="form-label">Primer apellido</label>
                        <input v-model="user.last_name1" type="text" id="last_name1" class="form-control" :required="!isEditMode" />
                    </div>

                    <div class="mb-3">
                        <label for="last_name2" class="form-label">Segundo apellido</label>
                        <input v-model="user.last_name2" type="text" id="last_name2" class="form-control" :required="!isEditMode" />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input v-model="user.email" type="email" id="email" class="form-control" :required="!isEditMode" />
                    </div>

                    <div class="mb-3" v-if="!isEditMode"> <!--Contraseña solo aparecerá cuando no estemos en isEditMode: cuando estemos creando-->
                        <label for="password" class="form-label">Contraseña</label>
                        <input v-model="user.password" type="password" id="password" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">País</label>
                        <input v-model="user.country" type="text" id="country" class="form-control" :required="!isEditMode"/>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">Ciudad</label>
                        <input v-model="user.city" type="text" id="city" class="form-control" :required="!isEditMode" />
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input v-model="user.phone" type="number" id="phone" class="form-control" :required="!isEditMode" />
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input v-model="user.photo" type="text" id="photo" class="form-control" />
                    </div>

                    <div class="mb-3" v-if="!isEditMode">
                        <label for="biography" class="form-label">Biografía</label>
                        <textarea v-model="user.biography" type="text" id="biography" class="form-control" rows="10"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" @click="cancelEdit">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardad cambios</button>
                    </div>
                
                </form>
            </div>
        </div>
    </div>
</template>

<style>
.card-header{
    background-color: #007bff;
    color: white;
}

.card-body{
    background-color: #f8f9fa;
}

form {
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

input.form-control,
textarea.form-control {
    border-radius: 12px;
    font-size: 0.95rem;
    padding: 10px 14px;
}

</style>