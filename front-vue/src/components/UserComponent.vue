<script setup>
import { ref, onMounted, computed} from 'vue';
import { useRouter } from 'vue-router'; 
import { getUsers, deleteUser, } from '@/services/userService';
import Swal from 'sweetalert2';


const router = useRouter(); 
const users = ref([]);
const searchQuery = ref(""); //para buscar

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
const handleDeleteUser = async (userId) => {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: 'Esta acción eliminará al usuario permanentemente.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      await deleteUser(userId);
      users.value = users.value.filter(user => user.id !== userId);
      Swal.fire('Eliminado', 'El usuario ha sido eliminado.', 'success');
    } catch (error) {
      Swal.fire('Error', 'No se pudo eliminar el usuario.', 'error');
    }
  }
};


//Filtro de búsqueda
const filteredUsers = computed(()=>{
    if(!users.value) return []; //si los usuarios no se han cargado aún, devolver un array vacío

    return users.value.filter(user=>{
        return user.name.toLowerCase().includes(searchQuery.value.toLocaleLowerCase()) ||
            user.email.toLocaleLowerCase().includes(searchQuery.value.toLocaleLowerCase());
    });
});


</script>

<template>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestión de usuarios</h5>
                <button @click="handleCreateUser" class="btn btn-light btn-sm rounded-pill">
                    <font-awesome-icon icon="plus" /> Añadir usuario
                </button>
            </div>
            
            <div class="card-body">
                <div class="form-group mb-3">
                    <input 
                        v-model="searchQuery"
                        type="text"
                        class="form-control w-50"
                        placeholder="Buscar por nombre o email"
                    />
                </div>
            
                <div class="table-responsive">
                    <table v-if="filteredUsers.length" class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in filteredUsers" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td>{{ user.lastName1 }} {{ user.lastName2 }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.roles.join(', ') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-2" @click="handleEditUser(user.id)">
                                        <font-awesome-icon icon="user-pen" />
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" @click="handleDeleteUser(user.id)">
                                        <font-awesome-icon icon="trash" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p v-else class="text-muted">No se encontraron usuarios.</p>
                </div><!--Fin div tabla-->
            </div><!--fin cardbody-->
        </div><!--fin card-->
    </div><!--fin container-->

</template>

<style scoped>
.card{
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.card-header{
    background-color: #6c63ff;
    color: white;
    font-weight: 600;
}

.card-body{
    background-color: #f9f9f9;
}

.table th,
.table td {
    vertical-align: middle;
    font-size: 0.95rem;
}

.btn-outline-warning,
.btn-outline-danger {
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 0.85rem;
}

.btn-outline-warning:hover{
    background-color: #ffc107;
    color: black;
}

.btn-outline-danger:hover{
    background-color: #dc3545;
    color: white;
}

inout.form-control{
    border-radius: 20px;
}

</style>