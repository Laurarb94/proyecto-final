<script>
import AppliedOffersComponent from '@/components/AppliedOffersComponent.vue';
import applicationService from '@/services/applicationService';
import UserCoursesComponent from '@/components/UserCoursesComponent.vue';
import courseService from '@/services/courseService';
import Swal from 'sweetalert2';

export default {
    components: { 
      AppliedOffersComponent,
      UserCoursesComponent
    },

    data(){
        return {
            appliedOffers: [],
            enrrolledCourses: [],
            currentOfferIndex: 0,
            currentCourseIndex: 0,
            userId: null 
        };
    },

    async created() {
      this.userId = parseInt(localStorage.getItem('userId'));
      
      try {
        const response = await applicationService.getUserApplications(this.userId);
        this.appliedOffers = response.data || [];
        
        if (this.appliedOffers.length > 0) {
          this.currentOfferIndex = 0;
        } else {
          this.currentOfferIndex = -1;
        }
        
        console.log('Ofertas aplicadas:', this.appliedOffers);
      } catch (error) {
        console.error('Error al obtener las aplicaciones:', error);
      }

      try {
        const response = await courseService.getUserCourses(this.userId);
        this.enrrolledCourses = response.data || [];
      } catch (error) {
        console.log('Error al obtener los cursos: ', error);
      }
    
    },

    methods: {
      prevOffer(){
        if(this.currentOfferIndex > 0) this.currentOfferIndex--;
      }, 

      nextOffer(){
        if (this.currentOfferIndex < this.appliedOffers.length - 1) this.currentOfferIndex++;
      },

      prevCourse(){
        if(this.currentCourseIndex > 0) this.currentCourseIndex--;
      },

      nextCourse(){
        if(this.currentCourseIndex < this.enrrolledCourses.length -1) this.currentCourseIndex++;
      },

      async removeApplication(offerId){
      const result = await Swal.fire({
        title: '¿Estás seguro',
        text: '¿Quieres cancelar tu postulación a esta oferta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No, mantener',
        reverseButtons: true
      });

      if(result.isConfirmed){
        this.appliedOffers = this.appliedOffers.filter(o => o.id !== offerId);
        localStorage.setItem('appliedOffers', JSON.stringify(this.appliedOffers));
          if(this.currentOfferIndex >= this.appliedOffers.length){
            this.currentOfferIndex = Math.max(this.appliedOffers.length -1, 0);
          }

          Swal.fire(
            'Postulación cancelada!',
            'Te has desinscrito de la oferta correctamente',
            'success'
          );
      }

    },

    },

};
</script>

<template>
<div class="container">
   <h2 class="title">Mis ofertas de empleo</h2>
   <AppliedOffersComponent
       :appliedOffers = "appliedOffers"
       :currentOfferIndex = "currentOfferIndex"
       @prev-offer = "prevOffer"
       @next-offer = "nextOffer"
       @remove-application = "removeApplication"
   />

   <h2 class="title mt-5">Mis cursos</h2>
   <UserCoursesComponent
      :courses="enrrolledCourses"
      :currentCourseIndex="currentCourseIndex"
      @prev-course="prevCourse"
      @next-course="nextCourse"
   />
</div>


</template>

<style scoped>
.container{
  margin-top: 20px;
}

.title{
  margin-top: 10px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: #333;
}



</style>