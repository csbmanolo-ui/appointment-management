import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login.component';
import { LayoutComponent } from './components/layout/layout.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { PacientesComponent } from './pages/pacientes/pacientes.component';
import { DoctoresComponent } from './pages/doctores/doctores.component';
import { CalendarioComponent } from './pages/calendario/calendario.component';
import { CitasComponent } from './pages/citas/citas.component';
import { authenticationGuard } from './services/authentication-guard';

export const routes: Routes = [
  // 1. Login fuera del layout (ocupa toda la pantalla)
  { path: 'login', component: LoginComponent },

  // 2. Aplicación Principal (Protegida)
  {
    path: '',
    component: LayoutComponent, // Usamos el Layout hueco como contenedor
    canActivate: [authenticationGuard],
    children: [
      // Redirección por defecto al entrar: ir a las estadísticas
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },

      // AHORA Dashboard ES UN HIJO NORMAL (Solo muestra stats)
      { path: 'dashboard', component: DashboardComponent },

      { path: 'pacientes', component: PacientesComponent },
      { path: 'doctores', component: DoctoresComponent },
      { path: 'calendario', component: CalendarioComponent },
      { path: 'citas', component: CitasComponent }
    ]
  },

  // 3. Cualquier ruta desconocida -> Login
  { path: '**', redirectTo: 'login' }
];
