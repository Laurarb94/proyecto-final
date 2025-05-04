<script setup>
import img1 from '../assets/images/inicio.jpeg';
import img2 from '../assets/images/inicio2.jpg';
import img3 from '../assets/images/inicio3.jpg';

const imagePaths = [img1, img2, img3];

</script>

<template>
      <div class="container py-5">
    <div class="hero-section text-center text-white bg-primary py-5">
      <h1 class="display-4 mb-4">Encuentra tu próximo empleo</h1>
      <p class="lead mb-5">Conectamos a personas como tú con las mejores oportunidades laborales. Empieza hoy mismo.</p>
      <RouterLink to="/registerUser" class="btn btn-light btn-lg">Regístrate</RouterLink>
      <RouterLink to="/api/login" class="btn btn-outline-light btn-lg ms-3">Iniciar sesión</RouterLink>
    </div>

    <!-- Tarjetas explicativas -->
    <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
      <div class="col" v-for="n in 3" :key="n">
        <div class="card shadow-sm">
          <!--Imagen dinámica-->
          <img :src="imagePaths[n-1]" class="card-img-top" alt="Imagen oportunidad {{ n }}">
          <div class="card-body">
            <h5 class="card-title">Oportunidad {{ n }}</h5>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec dolor at enim consectetur euismod non et enim.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
body {
  font-family: 'Roboto', sans-serif;
  background-color: #f4f7fa;
  color: #333;
}

.hero-section {
  background-color: #007bff; /* Azul sólido de fondo */
  color: white; /* Texto blanco */
  padding: 80px 20px;
  text-align: center;
}

.hero-section h1 {
  font-size: 3rem;
  font-weight: 700;
}

.hero-section p {
  font-size: 1.25rem;
  margin-bottom: 30px;
}

.card {
  border-radius: 10px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover{
  transform: scale(1.03);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.card-img-top {
  height: 200px;
  object-fit: cover;
}

.card-body {
  background-color: #f8f9fa;
}

.card-title {
  font-size: 1.25rem;
  font-weight: bold;
}

.card-text {
  color: #333;
}

.btn-light {
  background-color: white;
  color: #007bff;
}

.btn-light:hover {
  background-color: #0056b3;
  color: white;
}

.btn-outline-light {
  border-color: white;
  color: white;
  transition: all 0.3s ease;
}

.btn-outline-light:hover {
  background-color: white;
  color: #007bff;
}

</style>