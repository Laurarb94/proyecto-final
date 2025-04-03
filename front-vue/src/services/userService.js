import axios from "axios";

const API_URL ="http://127.0.0.1:8000/api/user";

//Obtener todos los usuarios
export const getUsers = async() =>{
    try{
        const response = await axios.get(API_URL);
        return response.data;
    }catch(error){
        console.log("Error al obtener usuarios: ", error);
        throw error;
    }
};

//Obtener a un usuario por su id
export const getUserById = async(userId) =>{
    try{
        const response = await axios.get(`${API_URL}/${userId}`);
        return response.data;
    }catch(error){
        console.log("Error al obtener al usuario: ", error);
    }
};

//Crear usuario
export const createUser = async (userData) =>{
    try{
        const response = await axios.post(API_URL, userData);
        return response.data;
    }catch(error){
        console.log("Error al crear el usuario: ", error.response ? error.response.data : error.message);
        throw error;
    }
};

//Editar al usuario
export const editUser = async (userId, userData) => {
    try {
        console.log("Enviando datos para editar al usuario: ", userData);
        const response = await axios.put(`${API_URL}/${userId}`, userData);
        return response.data;
    } catch (error) {
      console.log("Error al editar el usuario: ", error);
      throw error;
    }
};

//Eliminar al usuario
export const deleteUser = async (userId) =>{
    try{
        const response = await axios.delete(`${API_URL}/${userId}`);
        return response.data;
    }catch(error){
        console.log("Error al eliminar al usuario: ", error);
        throw error;
    }
};

//Registrar usuarios
export const registerUser = async (userData) => {
    try{
        console.log("Datos enviados al backend: ", userData);
        const response = await axios.post("http://127.0.0.1:8000/api/registration", userData);

        return response.data;

    }catch(error){
        console.log("Error al registrar al usuario: ", error.response ? error.response.data : error.message);
        throw error;
    }
};

