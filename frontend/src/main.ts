import { bootstrapApplication } from '@angular/platform-browser';
import { appConfig } from './app/app.config';
// Importamos la clase con el nuevo nombre
import { AppComponent } from './app/app.component';

bootstrapApplication(AppComponent, appConfig)
  .catch((err) => console.error(err));
