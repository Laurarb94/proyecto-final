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
};

