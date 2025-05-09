import axios from "axios";

const API_URL = 'http://localhost:8000/api'

export default {
    applyToOffer(offerId, userId) {
        const fullUrl = `${API_URL}/${offerId}/apply`;

        console.log(`URL: ${fullUrl}`);
        console.log('userId:', userId);

        return axios.post(
            fullUrl, 
            { userId }, // Body
            {
                headers: {
                    'Content-Type': 'application/json' // sin esto te daba error contínuamente!
                }
            }
        );
    },

    //función para obtener las postulaciones del usuario
    async getUserApplications (userId) {
        try {
            const response = await axios.get(`${API_URL}/user/${userId}/applications`);
            return response;
        } catch (error) {
            throw error;
        }
    },

    //Eliminar postulación del usuario porid
    async deleteApplication(userId, offerId){
        try {
            const fullUrl = `${API_URL}/user/${userId}/applications/${offerId}`;
            console.log(`URL de la solicitud DELETE: ${fullUrl}`);
    
            const response = await axios.delete(fullUrl);
            return response.data;
        } catch (error) {
            console.error('Error al eliminar la aplicación:', error);
            throw error;
        }
    }



}