import { Component, inject } from '@angular/core'; // <-- Importa 'inject'
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth'; // <-- Importa tu servicio

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard { // <-- Recuerda, tu clase se llama 'Dashboard'

  // "Inyectamos" las herramientas que necesitamos
  // (Esta es la forma moderna de 'constructor' para 'inject')
  private authService = inject(AuthService);
  private router = inject(Router);

  /**
   * Esta función se llama cuando el usuario pulsa "Cerrar Sesión"
   */
  onLogout(): void {
    console.log('Cerrando sesión...');

    // 1. Llama al "mensajero" para borrar el token
    this.authService.logout();

    // 2. Redirige al usuario de vuelta al login
    this.router.navigate(['/login']);
  }

}
