import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = 'http://127.0.0.1:8000/api';


  constructor(private http: HttpClient) { }

  /**
   * Llama al endpoint /api/login de Laravel.
   */
  login(credenciales: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/login`, credenciales).pipe(
      tap(respuesta => {
        if (respuesta && respuesta.access_token) {
          this.guardarToken(respuesta.access_token);
          console.log('¡Login exitoso! Token guardado en localStorage.');
        }
      })
    );
  }

  /**
   * Cierra la sesión: borra el token del localStorage.
   */
  logout(): void {
    localStorage.removeItem('token');
    console.log('Sesión cerrada, token borrado de localStorage.');
  }

  /**
   * Guarda el token en el almacén del navegador (localStorage).
   */
  private guardarToken(token: string): void {
    localStorage.setItem('token', token);
  }

  /**
   * Recupera el token del localStorage.
   */
  getToken(): string | null {
    return localStorage.getItem('token');
  }

  /**
   * Comprueba si el usuario está autenticado (si existe un token).
   */
  isLoggedIn(): boolean {
    return !!this.getToken();
  }
}
