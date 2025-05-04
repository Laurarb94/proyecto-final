import { ref } from 'vue';

const userId = ref(localStorage.getItem('userId'));
const token = ref(localStorage.getItem('token'));
const isAuthenticated = ref(!!userId.value);

export function useAuth(){
    const login = (id, jwt) => {
        userId.value = id;
        token.value = jwt;
        localStorage.setItem('userId', id);
        localStorage.setItem('token', jwt);
        isAuthenticated.value = true;
    };

    const logout = () =>{
        userId.value = null;
        token.value = null;
        localStorage.removeItem('userId');
        localStorage.removeItem('token');
        isAuthenticated.value = false;
    }

    return{
        userId,
        token,
        isAuthenticated, 
        login,
        logout
    };
};