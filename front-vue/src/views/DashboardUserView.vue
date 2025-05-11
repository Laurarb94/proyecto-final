<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

import { logoutUser } from '@/services/authService'
import { getUserById } from '@/services/userService'
import jobOfferService from '@/services/jobOfferService'
import categoryService from '@/services/categoryService'
import applicationService from '@/services/applicationService'
import courseService from '@/services/courseService'
import { useAuth } from '@/composables/useAuth'

import UserComponent from '@/components/UserComponent.vue'
import UserCoursesComponent from "@/components/UserCoursesComponent.vue"
import UserMessagesComponent from "@/components/UserMessagesComponent.vue"
import ProfileCardComponent from '@/components/ProfileCardComponent.vue'
import CategoryFilterComponent from '@/components/CategoryFilterComponent.vue'
import AppliedOffersComponent from '@/components/AppliedOffersComponent.vue'
import OfferListComponent from '@/components/OfferListComponent.vue'

const router = useRouter()
const {role} = useAuth()

// Estado
const user = ref({})
const offers = ref([])
const categories = ref([])
const subcategories = ref([])
const selectedSubcategory = ref(null)
const selectedSubcategoryId = ref(null)
const filteredOffers = ref([])
const filteredCourses = ref([])
const appliedOffers = ref([])
const appliedCourses = ref([])
const courses = ref([])
const userId = ref(null)

const currentView = ref('offers')
const searchQuery = ref('')
const currentPage = ref(1)
const totalPages = ref(1)

const isAdmin = computed(() => role.value === 'ROLE_ADMIN')

// Ciclo de vida
onMounted(async () => {
  await fetchUserData()
  await fetchCategories()
  await fetchOffers()
})

// Métodos
async function fetchUserData() {
  try {
    const id = localStorage.getItem('userId')
    if (id) {
      userId.value = parseInt(id)
      user.value = await getUserById(userId.value)
      const storedApplications = localStorage.getItem('appliedOffers')
      if (storedApplications) {
        appliedOffers.value = JSON.parse(storedApplications)
      } else {
        await fetchApplications()
      }
    }
  } catch (error) {
    console.error('Error al obtener datos del usuario:', error)
  }
}

async function fetchApplications(){
  try {
    const response = await applicationService.getUserApplications(userId.value)
    appliedOffers.value = response.data || []
  } catch (error) {
    console.log('Error al obtener las aplicaciones: ', error)
  }
}

async function fetchCategories() {
  try {
    categories.value = await categoryService.getAll()
  } catch (error) {
    console.error('Error al obtener categorías:', error)
  }
}

async function fetchOffers() {
  try {
    offers.value = await jobOfferService.getAll()
  } catch (error) {
    console.error('Error al obtener ofertas:', error)
  }
}

function selectSubcategory(subcategory) {
  selectedSubcategory.value = subcategory.name || subcategory
  selectedSubcategoryId.value = subcategory.id
  fetchFilteredOffers()
  fetchFilteredCourses()
}

function clearFilter() {
  selectedSubcategory.value = null
  selectedSubcategoryId.value = null
  filteredOffers.value = []
  filteredCourses.value = []
}

async function fetchFilteredOffers() {
  try {
    const allOffers = await jobOfferService.getAll()
    filteredOffers.value = allOffers.filter(offer =>
      offer.subcategory && offer.subcategory.id === selectedSubcategoryId.value
    )
  } catch (error) {
    console.error('Error al filtrar ofertas:', error)
  }
}

async function fetchFilteredCourses() {
  try {
    const allCourses = await courseService.getAll()
    courses.value = allCourses
    filteredCourses.value = allCourses.filter(course =>
      course.subcategory === selectedSubcategory.value
    )
  } catch (error) {
    console.error('Error al filtrar cursos:', error)
  }
}

function handleApplyOffer(offerId) {
  const offerToApply = offers.value.find(offer => offer.id === offerId)
  if (offerToApply) {
    applyToOffer(offerId)
  }
}

async function applyToOffer(offerId) {
  try {
    await applicationService.applyToOffer(offerId, userId.value)
    const applied = offers.value.find(offer => offer.id === offerId)
    if (applied) {
      appliedOffers.value.push(applied)
      offers.value = offers.value.filter(offer => offer.id !== offerId)
      localStorage.setItem('appliedOffers', JSON.stringify(appliedOffers.value))
    }

    Swal.fire({
      icon: 'success',
      title: '¡Postulación exitosa!',
      text: '¿Qué te gustaría hacer ahora?',
      showCancelButton: true,
      confirmButtonText: 'Ver mis ofertas',
      cancelButtonText: 'Seguir navegando',
      reverseButtons: true
    }).then(result => {
      if (result.isConfirmed) router.push({ name: 'myOffers' })
    })
  } catch (error) {
    Swal.fire('Error', 'Hubo un problema al postularte', 'error')
  }
}

async function handleApplyCourse(courseId) {
  try {
    await courseService.applyToCourse(courseId, userId.value)
    const applied = courses.value.find(course => course.id === courseId)
    if (applied) {
      appliedCourses.value.push(applied)
      courses.value = courses.value.filter(course => course.id !== courseId)
      filteredCourses.value = filteredCourses.value.filter(course => course.id !== courseId)
      localStorage.setItem('appliedCourses', JSON.stringify(appliedCourses.value))
    }

    Swal.fire({
      icon: 'success',
      title: '¡Inscripción exitosa!',
      text: '¿Qué te gustaría hacer ahora?',
      showCancelButton: true,
      confirmButtonText: 'Ver mis cursos',
      cancelButtonText: 'Seguir explorando',
      reverseButtons: true
    }).then(result => {
      if (result.isConfirmed) router.push({ name: 'myCourses' })
    })
  } catch (error) {
    Swal.fire('Error', 'Hubo un problema al inscribirte', 'error')
  }
}

async function logout() {
  try {
    await logoutUser()
    localStorage.removeItem('token')
    localStorage.removeItem('userId')
    localStorage.removeItem('appliedOffers')
    router.push('/')
  } catch (error) {
    console.error('Error en logout:', error)
  }
}

function goToProfile() {
  if (user.value) {
    router.push({ name: 'profile', params: { userId: user.value.id } })
  } else {
    console.error('No se pudo encontrar al usuario')
  }
}

function goToOffers() {
  router.push({ name: 'myOffers' })
}

function goToCourses() {
  router.push({ name: 'myCourses' })
}

function goToMessages() {
  router.push({ name: 'messages' })
}

function goToAdminPanel(){
  router.push({ name: 'users'})
}

</script>

<template>
 <div class="container my-4">

    <!-- Banner de Bienvenida/Introducción, solo se ve cuando no hay una oferta seleccionada -->
    <div class="intro-container fade-in" v-if="!selectedSubcategoryId">
      <div class="intro-banner">
      <h2>Bienvenida/o a nuestra plataforma de búsqueda de empleo</h2>
      <p>Encuentra las mejores ofertas de empleo, postúlate fácilmente y gestiona tu perfil profesional.</p>
      <p>Conéctate con personas que, al igual que tú, están listas para reinventarse profesionalmente.</p>
      <p>Apúntate a nuestros cursos y destaca en tu próximo empleo. ¡El futuro está a tu alcance!</p>
    </div>
    <img src="../assets/images/fotoInicio.png" alt="búsqueda de empleo" class="intro-image" />
    </div>


    <div 
        class="row justify-content-center align-items-start fade-in"
        v-for="offer in filteredOffers"
        :key="offer.id">
      
        <!-- Columna de la OFERTA -->
        <div class="col-md-6 d-flex">
          <div class="job-card flex-fill">
            <div class="job-header"> <h5>{{ offer.title }}</h5> </div>
            <p class="job-description">{{ offer.description }}</p>
            <button class="btn btn-outline-primary" @click="handleApplyOffer(offer.id)"> Aplicar </button>
          </div>
        </div>
        
        <!-- Columna del CURSO relacionado -->
        <div class="col-md-6 d-flex">
          <div class="course-card flex-fill">
            <div class="info-course-msg mb-2">
              <strong>¡Mejora tus oportunidades!</strong><br /> Apúntate a este curso para destacarte en esta oferta:
            </div>
            <div v-if="filteredCourses.length > 0">
              <div class="course-header"> <h5>{{ filteredCourses[0].title }}</h5> </div>
              <p class="course-description">{{ filteredCourses[0].content }}</p>
             <button class="btn btn-outline-primary" @click="handleApplyCourse(filteredCourses[0].id)">Apúntate</button>
            </div>
          </div>
        </div>
    </div>

      <!-- Barra de búsqueda (filtros) -->
      <div class="search-bar">
        <CategoryFilterComponent
          :categories="categories"
          @subcategory-selected="selectSubcategory"
          class="w-75"
        />
      </div>

    <!--Limpiar el filtro-->
    <div v-if="selectedSubcategoryId" class="mb-3 text-end">
      <button class="btn btn-outline-secondary" @click="clearFilter">Limpiar filtro</button>
    </div>

    <!-- Menú de Usuario -->
    <div class="user-menu">
      <button class="btn btn-secondary" @click="goToProfile">Mi Perfil</button>
      <button class="btn btn-secondary" @click="goToOffers">Ofertas de Empleo</button>
      <button class="btn btn-secondary" @click="goToCourses">Mis Cursos</button>
      <button class="btn btn-secondary" @click="goToMessages">Mensajes</button>

      <!--Solo si es admin-->
      <button v-if="isAdmin" class="btn btn-secondary" @click="goToAdminPanel">Panel de administrador</button>
    </div>

  </div>

</template>

<style scoped>
.intro-container {
  display: flex;
  align-items: center;
  gap: 20px;
  width: 100%;
}

/* Banner de introducción */
.intro-banner {
  padding: 2rem;
  border-radius: 10px;
  margin-bottom: 2rem;
  text-align: justify;
  flex: 1;
}

.intro-image{
  width: 300px;
  max-width: 200%;
}

.intro-banner h2 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.intro-banner p {
  font-size: 1.1rem;
  color: #555;
}


/* Barra de búsqueda */
.search-bar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 2rem;
}

.search-bar input {
  width: 75%;
  border-radius: 25px;
  padding: 10px;
  font-size: 1.1rem;
}

.search-bar button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.search-bar button:hover {
  background-color: #0056b3;
}

/* Menú de usuario */
.user-menu {
  display: flex;
  justify-content: flex-start;
  gap: 15px;
  margin-bottom: 2rem;
}

.user-menu button {
  background-color: #f1f1f1;
  color: #333;
  border: none;
  padding: 10px 20px;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.user-menu button:hover {
  background-color: #007bff;
  color: white;
}

/* Tarjetas de ofertas de empleo */
.job-card,
.course-card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 20px;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
  min-height: 300px;
}

.flex-fill{
  flex:1;
}

.job-card:hover,
.course-card:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.job-header,
.course-header {
  font-size: 1.2rem;
  color: #6c63ff; /* morado suave */
  margin-bottom: 0.5rem;
}

.job-card h5,
.course-card h5 {
  font-size: 1.2rem;
  font-weight: bold;
  margin: 0;
}

.job-description, 
.course-description {
  font-size: 1rem;
  color: grey;
  margin-bottom: 1rem;
}

.job-card button, 
.course-card button {
  background-color: transparent;
  color: grey;
  border: 1px solid #6c63ff; /* borde morado */
  padding: 8px 20px;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.job-card button:hover, 
.course-card button:hover {
  background-color: #6c63ff;
  color: #fff;
}

.info-course-msg {
  background: linear-gradient(135deg, #f2f4f8, #e6e9f0); /* tonos grises claros azulados */
  color: #3c3f4a; /* gris oscuro suave */
  padding: 16px;
  border-left: 4px solid #6c63ff; /* acento morado */
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 500;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 1rem;
}


.fade-in {
  animation: fadeIn 0.7s ease-in-out both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
