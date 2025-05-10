import api from "./api";

export default{
    async sendMessage(receiverId, content){
        const response = await api.post('/messages', {
            receiver_id: receiverId,
            content: content,
        });
        return response.data;
    },

    async getConversation(userId){
        const response = await api.get(`/messages/conversation/${userId}`);
        return response.data;
    },

    async getUsers(){
        const response = await api.get('/users-message');
        return response.data
    }

}; //cierre export