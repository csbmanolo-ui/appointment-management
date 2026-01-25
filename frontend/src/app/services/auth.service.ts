import { Injectable, inject} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = 'http://127.0.0.1:8000/api';
  private router = inject(Router);

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
  logout() {
    // 1. Intentamos avisar al backend para que anule el token
    this.http.post(`${this.apiUrl}/logout`, {}).subscribe({
      next: () => this.cerrarSesionLocal(),
      error: () => this.cerrarSesionLocal() // Si falla el server, cerramos igual por seguridad
    });
  }

  private cerrarSesionLocal() {
    // 2. Borramos el token del navegador
    localStorage.removeItem('token');
    // 3. Te echamos a la pantalla de login
    this.router.navigate(['/login']);
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
    return !!localStorage.getItem('token');
  }
}
