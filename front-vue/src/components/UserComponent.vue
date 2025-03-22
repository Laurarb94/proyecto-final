<script setup>
import { ref, onMounted} from 'vue';
import { useRouter } from 'vue-router'; 
import { getUsers, deleteUser, } from '@/services/userService';


const router = useRouter(); 

const users = ref([]);

//Listar a los usuarios:

onMounted(async ()=>{
    try{
        users.value = await getUsers(); //users.value actualiza la variable reactiva = await getUser llama al servicio y espera
                                        //a que la API responda
    }catch(error){
        console.log("Error al cargar usuarios: ", error);
    }
});

//Crear un usuario:
const handleCreateUser = async () =>{
    router.push({name: 'createUser'});
}

//Editar a los usuarios: 
const handleEditUser = async (userId) =>{
    router.push({name: 'editUser', params: {id: userId} }); //redirigir al usuario a la página para editar
}

//Eliminar a un usuario:
const handleDeleteUser = async (userId) =>{
    try{
        await deleteUser(userId); //llamas al servicio de eliminar
        users.value = users.value.filter(user => user.id !== userId); //después de eliminar al usuario, recargas lalista de usuarios
    }catch(error){
        console.log("Error al eliminar al usuario: ", error);
    }
}


</script>

<template>
    <div class="container">
        <table v-if="users.length" class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Primer apellido</th>
                    <th>Segundo apellido</th>
                    <th>Email</th>
                    <th>País</th>
                    <th>Ciudad</th>
                    <th>Teléfono</th>
                    <th>Foto</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td> {{ user.name }} </td>
                    <td> {{ user.lastName1 }} </td>
                    <td> {{ user.lastName2 }} </td>
                    <td> {{ user.email }} </td>
                    <td> {{ user.country }} </td>
                    <td> {{ user.city }} </td>
                    <td> {{ user.phone }} </td>
                    <td> {{ user.photo }} </td>
                    <td> {{ user.roles }} </td>
                    <td>
                        <font-awesome-icon icon="plus" class="my-icon" @click="handleCreateUser(user.id)"></font-awesome-icon>
                        <font-awesome-icon icon="user-pen" class="my-icon" @click="handleEditUser(user.id)" />
                        <font-awesome-icon icon="trash" @click="handleDeleteUser(user.id)"/>
                    </td>
                </tr>
            </tbody>
        </table>
        <p v-else>Cargando usuarios...</p>
    </div>
</template>

<style scoped>
  .my-icon{
    margin-right: 10px;
  }




</style>