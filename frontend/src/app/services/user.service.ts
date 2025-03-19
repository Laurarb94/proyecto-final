import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  //llamas a la api de symfony
  private apiUrl = 'http://127.0.0.1:8000/api/user'; 

  constructor(private http: HttpClient) { }

  //Métodos para pintar métodos de la api:
  //Obtener los usuarios:
  getUsers(): Observable <any>{
    return this.http.get(this.apiUrl);
  }

  //Obtener un usuario por su id
  getUserById(id: number): Observable <any>{
    return this.http.get(`${this.apiUrl}/${id}`);
  } 

  //Crear un usuario
  createUser(useData: any): Observable <any>{
    return this.http.post(this.apiUrl, useData);
  }

  //Editar un usuario
  updateUser(id: number, userData: any): Observable <any>{
    return this.http.put(`${this.apiUrl}/${id}`, userData);
  }

  //Eliminar usuario
  deleteUser(id: number): Observable <any>{
    return this.http.delete(`${this.apiUrl}/${id}`);
  }




} //cierre del servicio
