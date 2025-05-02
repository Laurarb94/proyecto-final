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
    }

}