<script setup>
import { ref, watch, onMounted } from 'vue'
import messageService from '@/services/messageService'

const props = defineProps({
    userId: {
        type: Number, 
        required: false
    }
})

const selectedUser = ref(null)
const messageContent = ref('')
const messages = ref([])

//Cargar mensajes cuando cambia el userId
watch(
  () => props.userId, //cada vez que cambie el userId, activará newId
  (newId) => { //es el nuevo valor de props.userid
    console.log('nuevo userId recibido: ', newId)
    if (newId) {
      fetchMessages(newId)
    }
  },
  { immediate: true } //hace que se ejecute al montar el componente si ya hay valor en userid
)


async function fetchMessages(userId){
    console.log('Llamando a fetchMessage con userId: ', userId)
    if(!userId) return 
    try {
        const conversation = await messageService.getConversation(userId)
       
        if (conversation.user) {
            selectedUser.value = conversation.user
            messages.value = conversation.messages || []
        } else if (conversation.status === 'No hay mensajes en esta conversación') {
            // fallback para conversaciones nuevas
            selectedUser.value = props.userId ? { id: props.userId, name: '' } : null
            messages.value = []
        }

    } catch (error) {
        console.log('Error al cargar los mensajes: ', error)
    }
}

async function sendMessage(){
    if(messageContent.value.trim()){
        try {
            await messageService.sendMessage(selectedUser.value.id, messageContent.value)
            console.log('Enviando mensaje a: ', selectedUser.value.id, "con contenido: ", messageContent.value)
            await fetchMessages(selectedUser.value.id)
            messageContent.value = ''
        } catch (error) {
            console.log('Error al enviar el mensaje: ', error)
        }
    }
}


</script>

<template>
   <div class="user-messages">
    <h3>Conversación con: {{ selectedUser ? selectedUser.name : 'Nadie' }}</h3>

    <div v-if="selectedUser">
      <div class="message-area">
        <!-- Mensajes existentes -->
        <div v-for="msg in messages" :key="msg.id" class="message">
          <p><strong>{{ msg.sender.name }}:</strong> {{ msg.content }}</p>
        </div>
      </div>

      <!-- Campo para escribir -->
      <textarea
        v-model="messageContent"
        placeholder="Escribe tu mensaje..."
        class="message-input"
      ></textarea>
      <button @click="sendMessage" class="send-button">Enviar</button>
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
    border-left: 2px solid #e0e0e0;
    background-color: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.user-messages h3{
  font-size: 1.4rem;
  margin-bottom: 1rem;
  color: #333;
}

.message-area{
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 15px;
    padding-right: 10px;
}

.message{
    background-color: linear-gradient(135deg, #f2f4f8, #e6e9f0);
    color: #3c3f4a;
    padding: 12px;
    border-left: 4px solid #6c63ff;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 0.95rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.message-input{
    width: 100%;
    height: 90px;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 10px;
    resize: none;
    margin-bottom: 10px;
}

.send-button{
    background-color: #6c63ff;
    color: white;
    padding: 10px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.send-button:hover{
    background-color: #574fd6;
}

</style>
  