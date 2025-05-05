<script>
export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    }
};
</script>

<template>
    <div class="car">
        
        <img 
            v-if="user.photo" 
            :src="`http://localhost:8000/uploads/profile_photos/${user.photo}`" 
            alt="Foto de perfil" 
            class="card-img-top profile-photo" 
        />
        <img v-else
            src="http://localhost:8000/uploads/default-photo/default-photo.jpeg"
            alt="Foto de perfil por defecto"
            class="card-igm-top profile-photo"
        />
        <p v-if="user.biography" class="card-text"> {{ user.biography }}</p>
        <a 
            v-if="user.cv" 
            :href= "`http://localhost:8000/uploads/cvs/${user.cv}`" 
            class="btn btn-primary cv" 
            target="_blank">
            Ver CV
        </a>
    </div>
</template>

<style scoped>
.profile-photo{
    border-radius: 50%;
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-bottom: 15px;
}

.card-text{
    text-align: justify;
}
</style>