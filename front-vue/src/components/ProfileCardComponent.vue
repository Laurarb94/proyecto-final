<script>
import axios from 'axios';

export default {
  props: {
    userId: {
      type: [String, Number],
      required: true
    }
  },

  data() {
    return {
      user: null
    };
  },
  
  async mounted() {
    try {
      const res = await axios.get(`http://localhost:8000/api/users/${this.userId}`);
      this.user = res.data;
    } catch (err) {
      console.error("Error al obtener usuario:", err);
    }
  },

  methods: {
    goBack() {
      this.$router.go(-1); // Regresa a la página anterior en el historial
    }
  }
};


</script>

<template>
    <div class="profile-card-wrapper" v-if="user">
        <div class="card profile-card shadow">
            <h3 class="card-title text-center mb-3">Tu perfil</h3>

            <div class="text-center">
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
            </div>

            <div class="card-body">
                <p><strong>Nombre:</strong> {{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</p>
                <p><strong>Ubicación:</strong> {{ user.city }}, {{ user.country }}</p>
                <p v-if="user.biography" class="card-text">
                    <strong>Mi carta de presentación:</strong><br />
                    <span class="biography-text">{{ user.biography }}</span>
                </p>
                <a 
                    v-if="user.cv" 
                    :href= "`http://localhost:8000/uploads/cvs/${user.cv}`" 
                    class="btn btn-primary cv" 
                    target="_blank">
                    Ver CV
                </a>
            </div>

            <div class="card-footer text-center mt-4">
                <button @click="goBack" class="btn btn-secondary">Volver</button>
            </div>

        </div>
    </div>

    <div v-else>
        <p>Cargando perfil...</p>
    </div>

</template>

<style scoped>
.profile-card-wrapper{
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.profile-card {
    width: 100%;
    max-width: 600px;
    padding: 30px;
    border-radius: 20px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-photo{
    border-radius: 50%;
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin: 0 auto 20px;
    display: block;
}

.card-body p{
    margin-bottom: 15px;
    font-size: 16px;
    line-height: 1.5;
}

.card-title{
    font-weight: bold;
    font-size: 24px;
    color: #333;
}

.btn-primary{
    display: block;
    width: 100%;
    margin-top: 20px;
}

.biography-text{
    text-align: justify;
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

.card-footer{
    padding-top: 20px;
    padding-bottom: 20px;
}

.card-footer .btn-secondary {
    width: 100%;
    max-width: 200px;
    margin: 0 auto;
    padding: 12px 0;
    font-size: 16px;
    background-color: #6c757d;
    border: none;
    color: white;
    border-radius: 25px;
    cursor: pointer;
}

.card-footer .btn-secondary:hover{
    background-color: #5a6268;
}

</style>