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
      class="search-bar"
    />

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
  width: 100%;
  padding: 20px;
}
.search-bar {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  font-size: 16px;
}
.user-item {
  padding: 8px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
}
.user-item:hover {
  background-color: #f1f1f1;
}
</style>
