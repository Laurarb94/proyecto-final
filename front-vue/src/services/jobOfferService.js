import axios from "axios";

const API_URL = 'http://localhost:8000/api/job-offers';

export default {
    getAll: async function () {
        try{
            const response = await axios.get(API_URL);
            console.log('Datos de la oferta: ', response.data);
            return response.data;
        }catch(error){
            console.log('Error al obtener las ofertas', error);
            throw error;
        }
    },

    getUserById: async function (id){
        try{
            const response = await axios.get(`${API_URL}/${id}`);
            return response.data;
        }catch(error){
            console.log('Error al obtener la oferta ', error);
            throw error;
        }
    },

    create: async function (offer){
        try {
            const response = await axios.post('http://localhost:8000/api/job-offers', offer, {
              headers: {
                'Content-Type': 'application/json',
              },
            });
            return response.data;
          } catch (error) {
            console.error('Error al crear la oferta', error);
            throw error;
          }
        },
        
    update: async function (offer) {
        try {
            const response = await axios.put(`${API_URL}/${offer.id}`, offer);
            return response.data;
        } catch (error) {
            console.log('Error al actualizar la oferta', error);
            throw error;
        }
    },

    delete: async function (id){
        try {
            const response = await axios.delete(`${API_URL}/${id}`);
            return response.data;
        } catch (error) {
            console.log('Error al eliminar la oferta', error);
            throw error;
        }
    }

    



} //cierre servicio