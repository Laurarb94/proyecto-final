<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  users: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['select-user'])

const searchTerm = ref('')

// computed para filtrar usuarios según la búsqueda
const filteredUsers = computed(() => {
  if (!searchTerm.value.trim()) return []
  return props.users.filter(user =>
    user.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
    user.email.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

// emitir al usuario seleccionado
function selectUser(user) {
  emit('select-user', user)
  searchTerm.value = ''
}

</script>

<template>
 <div class="user-list">
    <input 
      v-model="searchTerm" 
      type="text" 
      placeholder="Buscar usuario..."
      class="search-input"
    />

    <ul v-if="filteredUsers.length && searchTerm" class="results-dropdown">
      <li 
        v-for="user in filteredUsers"
        :key="user.id"
        @click="selectUser(user)"
        class="result-item"
        >
          {{ user.name }} {{ user.lastName1 }}
      </li>
    </ul>
<!---
    <div 
      v-for="user in filteredUsers"
      :key="user.id"
      @click="selectUser(user)"
      class="result-item"
    >
      {{ user.name }} || {{ user.email }}
    </div>
  -->
  </div>
</template>

<style scoped>
.user-list {
  position: relative;
  max-width: 400px;
  width: 100%;
  margin: 0 auto;
}

.search-input {
  width: 100%;
  padding: 12px 16px;
  font-size: 1rem;
  border-radius: 25px;
  border: 1px solid #ccc;
}

.results-dropdown {
  position: absolute;
  top: 100%; /* más exacto que 105% */
  left: 0;
  margin-top: 4px; /* si quieres un poco de separación */
  width: 100%;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  z-index: 1000;
  max-height: 250px;
  overflow-y: auto;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 0;
}


.result-item{
  padding: 10px 16px;
  cursor: pointer;
  list-style: none;
  transition: background-color 0.2s ease;
}

.result-item:hover{
  background-color: #f1f1f1;
}


</style>
