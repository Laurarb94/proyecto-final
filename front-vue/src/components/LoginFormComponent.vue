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

</script>

<template>
  <div class="container d-flex flex-column min-vh-100">
      <h2 class="mt-4 text-center mx-auto">Loguéate</h2>
     
      <!--Si viene del registro, mostrar mensaje para terminar de registrarse-->
      <div v-if="fromRegistration" class="alert alert-info text-center">
        <strong>¡Bienvenido/a!</strong>Para finalizar tu registro, por favor inicia sesión.
      </div>

    <div class="d-flex justify-content-center flex-grow-1">
      <div class="card shadow-sm p-4">

        <form @submit.prevent="handleLogin">
          <div class="mb-3">
            <input v-model="email" type="email" placeholder="Email" class="form-control" required />
          </div>

          <div class="mb-3">
            <input v-model="password" type="password" placeholder="Password" class="form-control" required />
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        
        </form>

        <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
      
      </div>
    </div>
  </div>
</template>

<style scoped>
.card{
  max-width: 400px;
  width: 100%;
  height: 300%;
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