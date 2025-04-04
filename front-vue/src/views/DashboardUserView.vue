<script>
export default {
  name: 'DashboardUserView',
};
</script>

<template>
  <div class="container my-4">
    <div class="row">

      <!-- Barra lateral izquierda (Perfil y opciones) -->
      <div class="col-md-3">
        <div class="card">
          <img src="" alt="Foto de perfil" class="card-img-top img-fluid" />
          <div class="card-body">
            <h5 class="card-title">Bienvenido</h5>
            <p class="card-text">Esta es tu biografía:</p>
            <p><a href="#" class="btn btn-primary">Ver CV</a></p>
          </div>
        </div>

        <!-- Menú de navegación -->
        <nav class="mt-4">
          <ul class="list-group">
            <li class="list-group-item"><a href="#">Mensajes</a></li>
            <li class="list-group-item"><a href="#">Ajustes</a></li>
            <li class="list-group-item"><a href="#">Cursos</a></li>
          </ul>
        </nav>
      </div>

      <!-- Centro: Ofertas activas -->
      <div class="col-md-6">
        <h2>Ofertas de empleo activas</h2>
        <div class="card-deck">
          <div class="card">
            <div class="card-body"> <!--usar vfor cuando tengas el código en symfony de las ofertas-->
              <h5 class="card-title">Título de la oferta</h5>
              <p class="card-text">Descripción de la oferta</p>
              <p><strong>Empresa:</strong> Empresa de la oferta</p>
              <p><strong>Ubicación:</strong> Ubicación de la oferta</p>
              <a href="#" class="btn btn-success">Postúlate</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Barra lateral derecha: Cursos y mensajes -->
      <div class="col-md-3">
        <h3>Cursos disponibles</h3>
        <div class="list-group">
          <div class="list-group-item"> <!--vfor cuando tengas el código-->
            <h5>Nombre del curso</h5>
            <p>Descripción del curso</p>
            <a href="#" class="btn btn-info btn-sm">Ver detalles</a>
          </div>
        </div>

        <h3 class="mt-4">Mensajes</h3>
        <div class="list-group">
          <div class="list-group-item"> <!--vfor cuando tengas el código-->
            <p>Texto del mensaje</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  margin-bottom: 20px;
}

.card-deck {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.list-group-item {
  padding: 10px;
  margin-bottom: 10px;
}

.card-title {
  font-size: 1.25rem;
}
</style>
