<script setup>

</script>

<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">TFG</a>
        <a class="navbar-brand" href="#">Quiénes somos</a>
        <a class="navbar-brand" href="#">Por qué quedarte con nosotros</a>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <RouterLink to="/" class="nav-link">Inicio</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink to="#" class="nav-link">Log in</RouterLink>
          </li>
          <li class="">
            <RouterLink to="/registerUser" class="nav-link">Regístrate</RouterLink>
          </li>
        </ul>
      </div>
    </nav>

    <RouterView />

    <!--
    <footer class="bg-dark text-white text-center py-3 mt-4">
      <p>© 2025 Mi TFG - Todos los derechos reservados</p>

    </footer>
  -->

  </div>
</template>

<style scoped>

</style>
