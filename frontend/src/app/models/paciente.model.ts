export interface Paciente {
  id: number;
  nombre: string;
  apellidos: string;
  telefono: string;
  email: string;
  seguro: string; // 'Sanitas', 'Privado', etc.
  ultimaVisita?: Date;
}
