<script setup>
//Organiza la página de los mensajes: búsqueda a la izq y chat a la derecha cuando se selecciona usu
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import UserListComponent from '@/components/UserListComponent.vue'
import UserMessagesComponent from '@/components/UserMessagesComponent.vue'
import { getUsers } from '@/services/userService'

const selectedUserId = ref(null);
const listaUsuarios = ref([])

const router = useRouter()

onMounted(async() => {
    //llamar al servicio para cargar usuarios
    const res = await getUsers()
    listaUsuarios.value = res
})

//Función para seleccoinar usuarios desde la list
function handleUserSelect(user){
    selectedUserId.value = user.id
}

function goBack(){
    router.back()
}


</script>

<template>
    <div class="message-view-wrapper">
        <button @click="goBack" class="back-button">Volver</button>
        
        <div class="message-page">
            <!--Lista de usuario-->
            <UserListComponent :users="listaUsuarios" @select-user="handleUserSelect" />

            <!--Mensajes del usuario seleccionado-->
            <UserMessagesComponent :user-id="selectedUserId" />
        </div>
    </div>
</template>

<style scoped>
.message-page{
    display: flex;
    justify-content: center;
    gap: 40px;
    padding-top: 60px;
    flex-wrap: wrap;
}

.message-view-wrapper{
    position: relative;
    padding: 20px;
}

.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background-color: #6c63ff;
    color: white;
    padding: 6px 14px;
    font-size: 0.9rem;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #574fd6;
}
</style>