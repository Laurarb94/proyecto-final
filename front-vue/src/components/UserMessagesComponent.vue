<script>
import messageService from "@/services/messageService";

export default {
    props: {
        userId: {
            type: Number,
            required: false
        }
    },

    data() {
        return {
            selectedUser: null,
            messageContent: '',
            messages: []
        };
    },

    watch: {
        userId(newId) {
            if(newId){
                this.fetchMessages(newId); //cuando se recibe un nuevo userId, obtener la conversación
            }
        }
    },

    methods: {
        //método para obtener mensajes e la conversación con el usuario seleccionado
        async fetchMessages(userId) {
            if(!userId) return; //no hacer nada si no hay userId
            try {
                const conversation = await messageService.getConversation(userId);
                this.messages = conversation.messages || [];
                this.selectedUser = conversation.user || null; //asigna el usuario de la conversación
            } catch (error) {
                console.error('Error al cargar los mensajes:', error);
            }
        },

        //Método para enviar un mensaje
        sendMessage() {
            if (this.messageContent.trim()) {
                messageService.sendMessage(this.selectedUser.id, this.messageContent).then(() => {
                    this.fetchMessages(this.selectedUser.id);  // Actualiza los mensajes después de enviar uno nuevo
                    this.messageContent = '';  // Limpia el campo de mensaje
                });
            }
        }
    },

    mounted() {
        if (this.userId) {
            this.fetchMessages(this.userId);
        }
    }
};
</script>

<template>
    <div class="user-messages">
        <h3>Conversación con: {{ selectedUser ? selectedUser.name : 'Nadie' }}</h3>
        <div v-if="selectedUser">
            <div class="message-area">
                <!--Aquí irán los mensajes-->
                <div v-for="message in messages" :key="message.id" class="message">
                    <p><strong>{{ message.sender.name }}</strong>
                        {{ messageContent }}
                    </p>
                </div>
                <textarea v-model="messageContent" placeholder="Escribe tu mensaje..." class="message-input"></textarea>
                <button @click="sendMessage" class="send-button">Enviar</button>
            </div>
        </div>
        <div v-else>
            <p>Selecciona un usuario para comenzar la conversación</p>
        </div>
    </div>
</template>
  

<style scoped>
.user-messages{
    flex: 1;
    padding: 20px;
    border-left: 2px solid #ccc;
}

.message-area{
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 10px;
}

.message{
    background-color: #f9f9f9;
    padding: 10px;
    margin-bottom: 5px;
    border-radius: 8px;
}

.message-input{
    width: 100%;
    height: 80px;
    margin-bottom: 10px;
}

.send-button{
    background-color: #4caf50;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.send-button:hover{
    background-color: #45a049;
}

</style>
  