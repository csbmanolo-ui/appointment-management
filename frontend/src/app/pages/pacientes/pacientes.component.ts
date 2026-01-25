import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatDialog, MatDialogModule } from '@angular/material/dialog';
import { MatTableModule } from '@angular/material/table';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatCardModule } from '@angular/material/card';
import { Paciente } from '../../models/paciente.model';
import { PacienteService } from '../../services/paciente.service';
import { PacienteDialogComponent } from './paciente-dialog/paciente-dialog.component';


@Component({
  selector: 'app-pacientes',
  standalone: true,
  imports: [
    CommonModule,
    MatTableModule,
    MatButtonModule,
    MatIconModule,
    MatCardModule,
    MatDialogModule,
    PacienteDialogComponent
  ],
  templateUrl: './pacientes.component.html',
  styleUrls: ['./pacientes.component.css']
})

export class PacientesComponent implements OnInit {
  //Inyectamos el servicio (El "mensajero" que va a Laravel)
  private pacienteService = inject(PacienteService);
  private dialog = inject(MatDialog);

  displayedColumns: string[] = ['id', 'nombre', 'seguro', 'telefono', 'acciones'];
  // Inicializamos la tabla VACÍA. Angular la llenará cuando lleguen los datos.
  dataSource: Paciente[] = [];

  // Al arrancar el componente, pedimos los datos
  ngOnInit() {
    this.cargarPacientes();
  }

  cargarPacientes() {
    this.pacienteService.getPacientes().subscribe({
      next: (data) => this.dataSource = data,
      error: (err) => console.error('Error:', err)
    });
  }

  // MÉTODO PARA ABRIR EL DIÁLOGO
  abrirDialogoNuevo() {
    const dialogRef = this.dialog.open(PacienteDialogComponent, {
      width: '400px'
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        // Si el usuario guardó (result tiene datos), llamamos al backend
        this.pacienteService.crearPaciente(result).subscribe({
          next: (nuevoPaciente) => {
            console.log('Paciente creado:', nuevoPaciente);
            this.cargarPacientes(); // Recargamos la tabla para ver al nuevo
          },
          error: (err) => alert('Error al crear paciente (¿Email duplicado?)')
        });
      }
    });
  }

  editarPaciente(paciente: Paciente) {
    const dialogRef = this.dialog.open(PacienteDialogComponent, {
      width: '400px',
      data: paciente // ¡Aquí pasamos los datos del paciente a la ventana!
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        // Si el resultado trae ID, es una actualización
        this.pacienteService.actualizarPaciente(paciente.id, result).subscribe({
          next: () => {
            console.log('Paciente actualizado');
            this.cargarPacientes();
          },
          error: (err) => alert('Error al actualizar.')
        });
      }
    });
  }

 eliminarPaciente(id: number) {
    if (confirm('¿Estás seguro de que quieres eliminar a este paciente?')) {
      this.pacienteService.eliminarPaciente(id).subscribe({
        next: () => {
          console.log('Paciente eliminado');
          this.cargarPacientes(); // <--- IMPORTANTE: Recargamos la tabla para ver que desaparece
        },
        error: (err) => {
          console.error('Error al eliminar:', err);
          alert('No se pudo eliminar al paciente.');
        }
      });
    }
  }
}
