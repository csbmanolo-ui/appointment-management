import { Component, inject, Inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MatDialogModule, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatSelectModule } from '@angular/material/select';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { PacienteService } from '../../../services/paciente.service';
import { DoctorService } from '../../../services/doctor.service';
import { Paciente } from '../../../models/paciente.model';
import { Doctor } from '../../../models/doctor.model';
import { MatIconModule } from '@angular/material/icon';

@Component({
  selector: 'app-cita-dialog',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, MatDialogModule,
    MatFormFieldModule, MatInputModule, MatButtonModule,
    MatSelectModule, MatDatepickerModule, MatNativeDateModule,
    MatIconModule
  ],
  templateUrl: './cita-dialog.component.html',
  styleUrls: ['./cita-dialog.component.css']
})
export class CitaDialogComponent implements OnInit {

  private fb = inject(FormBuilder);
  private dialogRef = inject(MatDialogRef<CitaDialogComponent>);

  // Inyectamos servicios para llenar los selectores
  private pacienteService = inject(PacienteService);
  private doctorService = inject(DoctorService);

  // Listas para los desplegables
  pacientes: Paciente[] = [];
  doctores: Doctor[] = [];

  citaForm: FormGroup;
  isEditMode: boolean = false;

  constructor(@Inject(MAT_DIALOG_DATA) public data: any) {
    this.citaForm = this.fb.group({
      paciente_id: ['', Validators.required],
      doctor_id: ['', Validators.required],
      fecha_hora_inicio: ['', Validators.required],
      fecha_hora_fin: ['', Validators.required],
      motivo_consulta: ['', Validators.required],
      notas_doctor: ['']
    });
  }

  ngOnInit(): void {
    // Cargar listas de Pacientes y Doctores
    this.pacienteService.getPacientes().subscribe(data => this.pacientes = data);
    this.doctorService.getDoctores().subscribe(data => this.doctores = data);

    // 2. CASO: CREAR NUEVA CITA (Clic en hueco vacío del calendario)
    if (this.data?.startStr) {
      // FullCalendar da ISO string. Ajustamos para input datetime-local
      const fechaInicio = this.data.startStr.substring(0, 16);

      // Calculamos fin (por defecto 30 min después)
      const fechaFinObj = new Date(this.data.start);
      fechaFinObj.setMinutes(fechaFinObj.getMinutes() + 30);


      const tzoffset = (new Date()).getTimezoneOffset() * 60000;
      const localISOTime = (new Date(fechaFinObj.getTime() - tzoffset)).toISOString().slice(0, 16);

      this.citaForm.patchValue({
        fecha_hora_inicio: fechaInicio,
        fecha_hora_fin: localISOTime
      });
    }

    if (this.data?.cita) {
        this.isEditMode = true;
        const c = this.data.cita;

        let inicio = c.fecha_hora_inicio;
        let fin = c.fecha_hora_fin;

        if (inicio && inicio.indexOf('T') === -1) {
             inicio = inicio.replace(' ', 'T').substring(0, 16);
        }
        if (fin && fin.indexOf('T') === -1) {
             fin = fin.replace(' ', 'T').substring(0, 16);
        }

        this.citaForm.patchValue({
            paciente_id: c.paciente_id,
            doctor_id: c.doctor_id,
            fecha_hora_inicio: inicio,
            fecha_hora_fin: fin,
            motivo_consulta: c.motivo_consulta,
            notas_doctor: c.notas_doctor
        });
    }
  }

  guardar() {
    if (this.citaForm.valid) {
      this.dialogRef.close(this.citaForm.value);
    }
  }

  cancelar() {
    this.dialogRef.close();
  }

  eliminar() {
    this.dialogRef.close('eliminar');
  }
}
