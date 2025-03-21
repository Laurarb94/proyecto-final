<script setup>
import { ref, onMounted} from 'vue';
import { getUsers } from '@/services/userService';

const users = ref([]);

onMounted(async ()=>{
    try{
        users.value = await getUsers(); //users.value actualiza la variable reactiva = await getUser llama al servicio y espera
                                        //a que la API responda
    }catch(error){
        console.log("Error al cargar usuarios: ", error);
    }
});
</script>

<template>
    <div>
        <h2>Lista de usuarios</h2>
        <ul v-if="users.length">
            <li v-for="user in users" :key="user.id">
                {{ user.name }} - {{ user.lastName1 }} - {{ user.email }} 
            </li>
        </ul>
        <p v-else>Cargando usuarios...</p>
    </div>
</template>

<style scoped>
h2{
    color: #42b983;
}
</style>