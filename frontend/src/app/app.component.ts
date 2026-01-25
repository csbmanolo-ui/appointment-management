import { Component } from '@angular/core';
import { CommonModule } from '@angular/common'; // <--- Importante para evitar errores raros
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet], // <--- Asegúrate de tener esto
  templateUrl: './app.html', // O app.component.html según tu archivo real
  styleUrls: ['./app.css']   // O app.component.css
})
// CAMBIA EL NOMBRE DE LA CLASE AQUÍ:
export class AppComponent {
  title = 'gestion-citas';
}
