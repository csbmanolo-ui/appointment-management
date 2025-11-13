import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon'
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../../services/auth';


@Component({
  selector: 'app-login',
  standalone: true, // <-- Tu app es Standalone
  imports: [
    ReactiveFormsModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatIconModule
  ],
  templateUrl: './login.html',
  styleUrl: './login.css'
})
export class Login { // <-- Usamos 'Login' como descubriste

  loginForm: FormGroup; // Esta es la variable que controla el formulario
  errorMessage: string | null = null; // Para mostrar errores

  hidePassword = true;

  // "Inyectamos" las herramientas que necesitamos
  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    // Creamos el "cerebro" del formulario
    this.loginForm = this.fb.group({
      // Definimos los campos y sus reglas
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required]]
    });
  }

  /**
   * Esta función se llama cuando el usuario pulsa "Enviar"
   */
  onSubmit() {
    if (this.loginForm.invalid) {
      // Si el formulario es inválido (ej. email vacío), se toca para mostrar errores
      this.loginForm.markAllAsTouched();
      return;
    }

    // Limpiamos errores previos
    this.errorMessage = null;

    // Llamamos al "Mensajero" (AuthService)
    this.authService.login(this.loginForm.value).subscribe({


      next: (respuesta) => {
        console.log('Respuesta del login:', respuesta);
        this.router.navigate(['/dashboard']);
      },

      // CASO DE ERROR (ej. 401 Credenciales incorrectas):
      error: (error) => {
        console.error('Error en el login:', error);

        // El error 401 que programamos en Laravel viene aquí
        if (error.status === 401) {
          this.errorMessage = 'Token inválido o ha caducado.';
        }
        // El error 422 (validación de Laravel) viene aquí
        else if (error.status === 422) {
          this.errorMessage = error.error.message;
        }
        // Otro error
        else {
          this.errorMessage = 'Error de conexión. Inténtalo de nuevo.';
        }
      }
    });
  }
}
