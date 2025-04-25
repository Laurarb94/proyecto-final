import axios from "axios";
import { getUserById } from "./userService";

const API_URL = 'http://localhost:8000/api/job-offers';

function getAuthHeaders(){
    const token = localStorage.getItem('token');
    return {
        headers: {
            Authorization: `Bearer ${token}`
        }
    };
}

export default {
    getAll: async function () {
        try{
            const response = await axios.get(API_URL);
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

    create: async function (offer) {
        try {
            const response = await axios.post(API_URL, offer, getAuthHeaders());
            return response.data;
        } catch (error) {
            console.log('Error al crear la oferta', error);
            throw error;
        }
    },

    update: async function (offer) {
        try {
            const response = await axios.put(`${API_URL}/${id}`, offer, getAuthHeaders());
            return response.data;
        } catch (error) {
            console.log('Error al actualizar la oferta', error);
            throw error;
        }
    },

    delete: async function (id){
        try {
            const response = await axios.delete(`{API_URL}/${id}`, getAuthHeaders());
            return response.data;
        } catch (error) {
            console.log('Error al eliminar la oferta', error);
            throw error;
        }
    }

    



} //cierre servicio