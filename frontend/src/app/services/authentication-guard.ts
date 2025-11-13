import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from './auth'; // <-- Importa tu servicio

export const authenticationGuard: CanActivateFn = (route, state) => {

  const authService = inject(AuthService); // <-- "Inyecta" el servicio
  const router = inject(Router); // <-- "Inyecta" el router

  if (authService.isLoggedIn()) {
    return true; // <-- ¡El usuario está logueado! Déjale pasar.
  }

  // Si no...
  console.log('AuthGuard: Usuario no logueado, redirigiendo a /login...');
  router.navigate(['/login']); // <-- Envíalo al login
  return false; // <-- No le dejes pasar
};
