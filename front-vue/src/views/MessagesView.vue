<script setup>
//Organiza la página de los mensajes: búsqueda a la izq y chat a la derecha cuando se selecciona usu
import { ref, onMounted } from 'vue'
import UserListComponent from '@/components/UserListComponent.vue'
import UserMessagesComponent from '@/components/UserMessagesComponent.vue'
import { getUsers } from '@/services/userService'

const selectedUserId = ref(null);
const listaUsuarios = ref([])

onMounted(async() => {
    //llamar al servicio para cargar usuarios
    const res = await getUsers()
    listaUsuarios.value = res
})

//Función para seleccoinar usuarios desde la list
function handleUserSelect(user){
    selectedUserId.value = user.id
}



</script>

<template>
    <div class="message-page">
        <!--Lista de usuario-->
        <UserListComponent :users="listaUsuarios" @select-user="handleUserSelect" />

        <!--Mensajes del usuario seleccionado-->
        <UserMessagesComponent :user-id="selectedUserId" />
    </div>
</template>

<style scoped>
.message-page{
    display: flex;
    justify-content: center;
    gap: 40px;
    padding: 40px;
    flex-wrap: wrap;
}
</style>