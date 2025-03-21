<script setup>
import UserComponent from '@/components/icons/UserComponent.vue';
</script>
<template>
    <div class="text-center">
        <h1>Usuarios de la aplicación: </h1>

        <UserComponent />
        <RouterLink to="/" button class="btn-btn-primary mt-3">Volver al inicio</RouterLink>
    </div>
</template>