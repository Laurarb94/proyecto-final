<script setup>
import { logoutUser } from '@/services/authService';
import { useRouter } from 'vue-router';

const router = useRouter(); //obtener el router para redirigir

const logout = async() => {
    try {
        await logoutUser(); //llamas al servicio
        localStorage.removeItem('userId');
        localStorage.removeItem('token');
        router.push('/api/login');
    } catch (error) {
        console.log('Error al hacer logout: ', error);
    }
}

</script>

<template>
    <button @click="logout" class="btn btn-danger">Cerrar sesión</button>
</template>