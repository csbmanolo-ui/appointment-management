import { Component, inject } from '@angular/core';
import { Router, RouterModule } from '@angular/router'; // Importamos RouterModule para los links
import { AuthService } from '../../services/auth';

// --- Módulos de Material ---
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatListModule } from '@angular/material/list';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card'; // <-- AÑADE ESTA LÍNEA

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [
    RouterModule,
    MatSidenavModule,
    MatToolbarModule,
    MatListModule,
    MatIconModule,
    MatButtonModule,
    MatCardModule
  ],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard {

  private authService = inject(AuthService);
  private router = inject(Router);

  // Variable para saber si el usuario es Admin (lo usaremos para mostrar/ocultar opciones del menú)
  // Por ahora lo dejamos simple, luego lo conectaremos al rol real.
  isAdmin = true;

  onLogout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
