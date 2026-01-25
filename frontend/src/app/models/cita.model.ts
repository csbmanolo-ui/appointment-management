import { Paciente } from './paciente.model';
import { Doctor } from './doctor.model';

export interface Cita {
  id?: number;
  paciente_id: number;
  doctor_id: number;
  fecha_hora_inicio: string;
  fecha_hora_fin: string;
  motivo_consulta: string;
  notas_doctor?: string;
  estado?: string;

  // Datos extra que Laravel nos envía gracias al 'with()'
  paciente?: Paciente;
  doctor?: Doctor;
}
