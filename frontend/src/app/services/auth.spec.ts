import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http'; // <-- Importa el HttpClient

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  // ¡OJO! Esta es la URL de tu API.
  // La ponemos aquí directamente por ahora.
  private apiUrl = 'http://127.0.0.1:8000/api';

  // Pedimos a Angular que "inyecte" el motor (HttpClient)
  constructor(private http: HttpClient) { }

  // (Pronto añadiremos aquí la función de login)
  // login(credenciales: any) {
  //   return this.http.post(`${this.apiUrl}/login`, credenciales);
  // }

}
