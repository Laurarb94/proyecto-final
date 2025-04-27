<script setup>
import CreateJobOfferFormComponent from '@/components/CreateJobOfferFormComponent.vue';
import JobOfferListComponent from '@/components/JobOfferListComponent.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

//Verificar autenticación y rol
const checkAuthentication = () => {
    const token = localStorage.getItem('token');
    if(!token){
        router.push('/api/login'); //si no hay token redirigir al login
        return;
    }

    try {
        const tokenPayLoad = JSON.parse(atob(token.split('.')[1]));
        const userRole = tokenPayLoad.role;

        if(userRole !== 'ROLE_COMPANY'){
            router.push('/api/login'); //si no es empresa, redirigir al login
        }
    } catch (error) {
        console.log('Error al verificar el token: ', error);
        router.push('/api/login'); //si el token no es válido, redirigir al login
    }
}; 

//Ejecutar la verificacion al montar el componente
checkAuthentication();

</script>

<template>
    <div class="container my-5">
        <h1 class="mb-4 text-center">Panel de Empresa</h1>

        <div class="card p-4 shadow-sm mb-5">
            <h2 class="card-title mb-3">Crear nueva oferta
                <CreateJobOfferFormComponent />
            </h2>
        </div>

        <div class="card p-4 shadow-sm">
            <h2 class="card-title">Ofertas Publicadas
                <JobOfferListComponent />
            </h2>
        </div>
    </div>
</template>