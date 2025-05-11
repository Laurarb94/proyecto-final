<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import { loginUser } from '@/services/authService'

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const fromRegistration = ref(false)

const route = useRoute()
const router = useRouter()

onMounted(() => {
  if(route.query.fromRegistration){
    fromRegistration.value = true
  }
})


async function handleLogin() {
  if (!email.value || !password.value) {
    errorMessage.value = 'Por favor, ingresa un correo y contraseña válido'
    return
  }

  try {
    const response = await loginUser(email.value, password.value)
    console.log('Login exitoso:', response)

    const { login } = useAuth()
    login(response.id, response.token, response.role)

    const tokenPayload = JSON.parse(atob(response.token.split('.')[1]))
    console.log('Token Payload:', tokenPayload)

    const userRole = tokenPayload.role
    console.log('User Role:', userRole)

    localStorage.setItem('userRole', userRole)

    if (userRole === 'ROLE_USER' || userRole === 'ROLE_ADMIN') {
      router.push('/dashboardUser')
    } else if (userRole === 'ROLE_COMPANY') {
      router.push('/dashboardCompany')
    } else {
      console.log('Error: rol desconocido')
      router.push('/api/login')
    }

  } catch (error) {
    console.error('Error de login:', error)
    errorMessage.value = 'Email o contraseña incorrectos'
  }
}

function goBack(){
  router.go(-1)
}

</script>

<template>
<div class="container d-flex align-items-center min-vh-100 fade-in">
    <div class="row justify-content-center w-100">
      <div class="col-md-5 d-flex align-items-center">
        <img src="../assets/images/login.png" alt="Imagen Login" class="img-fluid login-img">
      </div>
      
      <div class="col-md-5">
        <div class="card shadow-sm rounded-4 p-4">
          <h2 class="text-center mb-4 fw-bold">Loguéate</h2>

          <div v-if="fromRegistration" class="alert alert-info text-center">
            <strong>¡Bienvenido/a!</strong> Para finalizar tu registro, por favor inicia sesión.
          </div>

          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <input v-model="email" type="email" placeholder="Email" class="form-control" required />
            </div>

            <div class="mb-4">
              <input v-model="password" type="password" placeholder="Contraseña" class="form-control" required />
            </div>

            <div class="d-flex justify-content-between">
              <button type="button" class="btn btn-outline-secondary" @click="goBack">Volver</button>
              <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card{
  max-width: 400px;
  width: 100%;
  height: auto;
  margin-top: 5%;
  border-radius: 12px;
  transition: all 0.3s ease;
  background-color: #fff;
}

.card-header {
  background-color: #fff;
  border-bottom: 2px solid #ddd;
  text-align: center;
  font-weight: bold;
  padding: 15px;
}

.card input{
  border-radius: 5px;
}

.card button{
  border-radius: 5px;
  font-weight: 600;
}

.card p{
  font-size: 0.9rem;
  color: #666
}

.alert-info {
  background-color: #e7f3fe;
  color: #084298;
  border: 1px solid #b6e0fe;
}

button {
  width: 48%;
}

button:focus {
  outline: none;
}

form {
  padding: 10px;
}


</style>