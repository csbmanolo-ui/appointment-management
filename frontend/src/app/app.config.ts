import { ApplicationConfig, importProvidersFrom, provideZoneChangeDetection } from '@angular/core';
import { provideRouter } from '@angular/router';
import { routes } from './app.routes';
import { ReactiveFormsModule } from '@angular/forms';

// 1. AÑADIMOS 'withInterceptors' y la referencia a tu interceptor
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { authInterceptor } from './services/auth.interceptor';

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }),
    provideRouter(routes),

    // 2. ACTIVAMOS EL INTERCEPTOR AQUÍ
    provideHttpClient(withInterceptors([authInterceptor])),

    importProvidersFrom(ReactiveFormsModule)
  ]
};
