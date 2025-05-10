<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { editUser } from '@/services/userService'

const props = defineProps({
  userId: {
    type: [String, Number],
    required: true
  }
})

const router = useRouter()
const user = ref(null)
const editingField = ref(null)

onMounted(async () => {
  try {
    const res = await axios.get(`http://localhost:8000/api/users/${props.userId}`)
    user.value = res.data
  } catch (err) {
    console.error('Error al obtener usuario:', err)
  }
})

function goBack() {
  router.go(-1)
}

function startEditing(field) {
  editingField.value = field
}

async function stopEditing() {
  if (editingField.value && user.value) {
    try {
      const updatedUser = { ...user.value } //crea una copia del usuario actual para enviar al back
      if (typeof updatedUser.roles === 'string') {
        updatedUser.roles = [updatedUser.roles]
      }
      await editUser(user.value.id, updatedUser) //aquí llamas a la api y así se guarda en bbss
    } catch (error) {
      console.log('Error al guardar los cambios: ', error)
    }
  }
  editingField.value = null
}

</script>

<template>
    <div class="profile-card-wrapper" v-if="user">
        <div class="card profile-card shadow">
            <h3 class="card-title text-center mb-3">Tu perfil</h3>

            <div class="text-center">
                <img 
                    v-if="user.photo" 
                    :src="`http://localhost:8000/uploads/profile_photos/${user.photo}`" 
                    alt="Foto de perfil" 
                    class="card-img-top profile-photo" 
                />
                <img v-else
                    src="http://localhost:8000/uploads/default-photo/default-photo.jpeg"
                    alt="Foto de perfil por defecto"
                    class="card-igm-top profile-photo"
                />
            </div>

            <div class="card-body">

                <p class="editable" @mouseenter="startEditing('fullName')" @mouseleave="stopEditing">
                    <strong>Nombre:</strong>
                    <span v-if="editingField !== 'fullName'">{{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</span>
                    <span v-else>
                        <input v-model="user.name" class="form-control mb-1" placeholder="Nombre" />
                        <input v-model="user.lastName1" class="form-control mb-1" placeholder="Primer apellido" />
                        <input v-model="user.lastName2" class="form-control" placeholder="Segundo apellido" />
                    </span>
                </p>
                
                <p class="editable" @mouseenter="startEditing('location')" @mouseleave="stopEditing">
                    <strong>Ubicación:</strong>
                    <span v-if="editingField !== 'location'">{{ user.city }}, {{ user.country }}</span>
                    <span v-else>
                        <input v-model="user.city" class="form-control mb-1" placeholder="Ciudad" />
                        <input v-model="user.country" class="form-control" placeholder="País" />
                    </span>
                </p>
                
                <p v-if="user.biography" class="editable" @mouseenter="startEditing('biography')" @mouseleave="stopEditing">
                    <strong>Mi carta de presentación:</strong><br />
                    <span v-if="editingField !== 'biography'" class="biography-text">{{ user.biography }}</span>
                    <span v-else><textarea v-model="user.biography" class="form-control" rows="4"></textarea></span>
                </p>
                
                <a 
                    v-if="user.cv" 
                    :href= "`http://localhost:8000/uploads/cvs/${user.cv}`" 
                    class="btn btn-primary cv" 
                    target="_blank">
                    Ver CV
                </a>
            </div>

            <div class="card-footer text-center mt-4">
                <button @click="goBack" class="btn btn-secondary">Volver</button>
            </div>

        </div>
    </div>

    <div v-else>
        <p>Cargando perfil...</p>
    </div>

</template>

<style scoped>
.profile-card-wrapper{
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.profile-card {
    width: 100%;
    max-width: 600px;
    padding: 30px;
    border-radius: 20px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-photo{
    border-radius: 50%;
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin: 0 auto 20px;
    display: block;
}

.card-body p{
    margin-bottom: 15px;
    font-size: 16px;
    line-height: 1.5;
}

.card-title{
    font-weight: bold;
    font-size: 24px;
    color: #333;
}

.btn-primary{
    display: block;
    width: 100%;
    margin-top: 20px;
}

.biography-text{
    text-align: justify;
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

.card-footer{
    padding-top: 20px;
    padding-bottom: 20px;
}

.card-footer .btn-secondary {
    width: 100%;
    max-width: 200px;
    margin: 0 auto;
    padding: 12px 0;
    font-size: 16px;
    background-color: #6c757d;
    border: none;
    color: white;
    border-radius: 25px;
    cursor: pointer;
}

.card-footer .btn-secondary:hover{
    background-color: #5a6268;
}

</style>