<script setup>
import UserComponent from '@/components/UserComponent.vue';
</script>
<template>
    <div class="text-center">
        <h1>Usuarios de la aplicación: </h1>

        <UserComponent />
        <RouterLink to="/dashboardUser" button class="btn btn-primary mt-3">Página principal</RouterLink>
    </div>
</template>