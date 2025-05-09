<script>
import UserListComponent from '@/components/UserListComponent.vue';
import UserMessagesComponent from '@/components/UserMessagesComponent.vue';
import messageService from '@/services/messageService';

export default{
    name: 'MessagesView',
    components: {
        UserMessagesComponent,
        UserListComponent
    },

    data(){
        return {
            selectedUser: null,
            users: []
        };
    },

    async mounted() {
        try {
            const allUsers = await messageService.getUsers(); // Obtienes los usuarios
            this.users = allUsers; // Guardas los usuarios en el 'data'
        } catch (error) {
            console.error('Error al cargar los usuarios:', error);
        }
    },

    methods: {
        handleUserSelection(user){
            console.log('Usuario seleccionado: ', user);
            this.selectedUser = user; //establece el usuario seleccionado
        }
    }
};
</script>

<template>
    <div class="messages-view">
        <h2>Tus Mensajes</h2>
        <div class="messages-container">
            <UserListComponent 
                @select-user="handleUserSelection"
                :users="users"  />
            <!--Pasar userId solo si el selectedUser está disponible: -->
            <UserMessagesComponent :userId="selectedUser ? selectedUser.id : null" />
        </div>
    </div>
</template>

<style scoped>
.messages-view {
    display: flex;
    justify-content: space-between;
}

.message-container{
    display: flex;
    width: 100%;
}

.user-list{
    width: 300px;
}

.user-messages{
    flex: 1;
    pad: 20pxs;
}

</style>