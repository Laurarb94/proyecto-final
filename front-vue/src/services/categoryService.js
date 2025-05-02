import axios from "axios";

const API_URL = 'http://localhost:8000/api/categories';

export default {
    getAll: async function (){
        try {
            const response = await axios.get(API_URL);
            return response.data;
        } catch (error) {
            console.log('Error al obtener las categorías: ', error);
            throw error;
        }
    }
}