export interface Doctor {
  id: number;
  nombre: string;
  apellidos: string;
  especialidad: string;
  telefono: string;
  email: string;
  color?: string; // El '?' significa que es opcional
  created_at?: string;
  updated_at?: string;
}
