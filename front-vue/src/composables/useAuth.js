import { ref } from 'vue';
const userId = ref(localStorage.getItem('userId'));
const token = ref(localStorage.getItem('token'));
const role = ref(localStorage.getItem('userRole'));
const isAuthenticated = ref(!!userId.value);

export function useAuth(){
    const login = (id, jwt, userRole) => {
        userId.value = id;
        token.value = jwt;
        role.value = userRole;

        localStorage.setItem('userId', id);
        localStorage.setItem('token', jwt);
        localStorage.setItem('userRole', userRole);

        isAuthenticated.value = true;
    };

    const logout = () =>{
        userId.value = null;
        token.value = null;
        role.value = null;

        localStorage.removeItem('userId');
        localStorage.removeItem('token');
        localStorage.removeItem('userRole');

        isAuthenticated.value = false;
    }

    return{
        userId,
        token,
        role, 
        isAuthenticated, 
        login,
        logout
    };
};