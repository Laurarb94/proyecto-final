import axios from "axios";

const API_URL= "http://127.0.0.1:8000/api/login";
const API_URL_LOGOUT = "http://127.0.0.1:8000/api/logout";

//Hacer el login
export const loginUser = async(email, password) =>{
  try{
    console.log('Datos que se envían a la API: ', {email, password});
    console.log('Email antes de la solicityd ', email);
    const response = await axios.post(API_URL, {
      email,
      password
    });

    console.log("Login exitoso", response.data);
    return response.data;
  }catch(error){
    console.log("error al hacer el login", error.response ? error.response.data : error.message);
    console.log('Email después de la solicitud' , email);
    throw error;
  }
};

//Hacer el logout
export const logoutUser = () => {
  //Eliminar token y usuario del localStorage:
  localStorage.removeItem("id");
  localStorage.removeItem("token");

  console.log("Logout local exitoso");
}


/*
export const logoutUser = async () =>{
  try{
    //Obtener el token almacenado en el localStorage
    const token = localStorage.getItem("token");

    //Ver si el token está disponible antes de enviar la solicidtud
    if(!token){
      throw new Error("No se encontró el token de autenticación");
    }

    console.log("Token enviado:", token);  // Asegúrate de que el token esté en el localStorage


    //haces solicitud post al endpoint del logout
    const response = await axios.post(API_URL_LOGOUT, {}, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    console.log("Logout exitoso", response.data);

    //Eliminar el token y el usuario de localStorage:
    localStorage.removeItem("id");
    localStorage.removeItem("token");

    return response.data;

  }catch(error){
    console.log("Error al hacer el logout: ", error.response ? error.response.data : error.message);
  }

}
*/

