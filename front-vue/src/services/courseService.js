import axios from "axios";

const API_URL = 'http://localhost:8000/api/courses';
const API_USER = 'http://localhost:8000/api/user'; 

export default{

    getAll: async function () {
        try {
            const response = await axios.get(API_URL);
            //console.log('Datos de los cursos: ', response.data);
            return response.data;
        } catch (error) {
            console.log('Error al obtener los cursos: ', error);
            throw error;
        }
    }, 

    getCourseById: async function (id){
        try {
            const response = await axios.get(`${API_URL}/${id}`)
            return response.data;
        } catch (error) {
            console.log('Error al obtener el curso ', error);
            throw error;
        }
    },

    //Obtener cursos de un usuario por su id
    getUserCourses: async function (userId){
        try {
            const response = await axios.get(`${API_USER}/${userId}/courses`);
            return response.data;
        } catch (error) {
            console.log('Error al obtener los cursos del usuario: ', error);
            throw error;
        }
    },

    //Obtener curso por subcategoría
    getCourseBySubcategory: async function (subcategoryId){
        try {
            const response = await axios.get(`${API_URL}/subcategory/${subcategoryId}`);
        } catch (error) {
            console.log('Error al obtener el curso por su subcategoría: ', error);
        }
    },

    //apuntarse a cursos
    applyToCourse: async function (userId, courseId){
        try {
            const response = await axios.post(`${API_USER}/${courseId}/courses/${userId}`);
            return response.data;
        } catch (error) {
            console.log('Error al inscribirse al curso: ', error);
            console.log(`${API_USER}/${userId}/courses/${courseId}`);
            console.log('userId:', userId);
            console.log('courseId:', courseId);
            throw error;
        }
    },

    //eliminar curso del usuario
    removeFromCourse: async function (userId, courseId){
        try {
            const response = await axios.delete(`${API_USER}/${userId}/courses/${courseId}`);
            return response.data;
        } catch (error) {
            console.log('Error al eliminar el curso: ', error);
            throw error;
        }
    }










} //cierre export default