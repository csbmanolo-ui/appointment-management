import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router'; // Importante para los links
import { AuthService } from '../../services/auth.service'; // Asegúrate de tener tu AuthService

@Component({
  selector: 'app-sidebar',
  standalone: true,
  imports: [CommonModule, RouterModule], // <--- AÑADIR RouterModule
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent {
  private authService = inject(AuthService); // Si no tienes AuthService aún, borra esta línea y el método logout
  private router = inject(Router);

  logout() {
    this.authService.logout(); // Si no tienes authService, usa: localStorage.clear();
    this.router.navigate(['/login']);
  }
}
