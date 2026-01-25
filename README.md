# appointment-management
He seguido un flujo de trabajo basado en Git, permitiendo una trazabilidad completa de cada funcionalidad. Como se observa en el repositorio, las actualizaciones se han realizado de forma atómica, separando claramente las responsabilidades del frontend y el backend, lo que facilita el mantenimiento y la escalabilidad del sistema

tack Tecnológico Utilizado
Backend: PHP 8.x con Laravel 11, utilizando Eloquent ORM para la gestión de modelos como Cita.php y Paciente.php.

Frontend: Angular 18, estructurado en servicios inyectables como cita.service.ts y auth.service.ts para una comunicación asíncrona eficiente.

Base de Datos: MySQL gestionado a través de XAMPP.

Funcionalidades Clave
Seguridad: Sistema de autenticación robusto con auth.service.ts y protectores de rutas (authentication-guard.ts).

Gestión Centralizada: Controladores API especializados (CitaController, DoctorController) para operaciones CRUD completas.

Arquitectura Modular: Separación clara entre frontend y backend en el mismo repositorio para facilitar el despliegue y mantenimiento.
