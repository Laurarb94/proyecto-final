<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    users: Array
})

const emit = defineEmits(['select-user'])

const searchTerm = ref('')

//computed para filtrar usuarios según la búsuqeda
const filteredUsers = computed(() => {
  if (!searchTerm.value.trim()) return props.users
  return props.users.filter(user =>
    user.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
    user.email.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

//emitir al usuario seleccionado
function selectUser(user) {
    emit('select-user', user)
}


</script>

<template>
    <div class="user-list">
        <!-- Barra de búsqueda -->
        <input 
            v-model="searchTerm" 
            type="text" 
            placeholder="Buscar usuario..." 
            class="search-bar"
        />

        <!-- Lista de usuarios filtrados -->
        <div 
            v-for="user in filteredUsers" 
            :key="user.id" 
            @click="selectUser(user)" 
            class="user-item"
        >
            {{ user.name }} || {{ user.email }}
        </div>
    </div>
</template>

<style scoped>
.user-list {
   width: 300px;
   margin-right: 20px;
   padding: 10px;
}

.search-bar{
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

.user-cards{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.user-card{
    background-color: #f1f1f1;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.user-card:hover{
    background-color: #e0e0e0;
}

.user-info{
    display: flex;
    align-items: center;
}

.user-avatar{
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.user-info h4{
    margin: 0;
}

</style>