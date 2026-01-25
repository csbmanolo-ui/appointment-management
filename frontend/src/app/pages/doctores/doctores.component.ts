import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatTableModule } from '@angular/material/table';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatDialog, MatDialogModule } from '@angular/material/dialog';

// Importamos los modelos y servicios de DOCTORES
import { DoctorService } from '../../services/doctor.service';
import { Doctor } from '../../models/doctor.model';
import { DoctorDialogComponent } from './doctor-dialog/doctor-dialog.component';

@Component({
  selector: 'app-doctores',
  standalone: true,
  imports: [
    CommonModule,
    MatTableModule,
    MatButtonModule,
    MatIconModule,
    MatDialogModule
  ],
  templateUrl: './doctores.component.html',
  styleUrls: ['./doctores.component.css']
})
export class DoctoresComponent implements OnInit {

  // 1. Inyectamos Servicio de Doctores y Diálogo
  private doctorService = inject(DoctorService);
  private dialog = inject(MatDialog);

  dataSource: Doctor[] = [];

  // 2. IMPORTANTE: Estas columnas deben coincidir EXACTAMENTE con los matColumnDef de tu HTML
  displayedColumns: string[] = ['id', 'nombre', 'especialidad', 'telefono', 'acciones'];

  ngOnInit() {
    this.cargarDoctores();
  }

 cargarDoctores() {
    this.doctorService.getDoctores().subscribe({
      next: (data) => {
        console.log('📢 DATOS RECIBIDOS DE LARAVEL:', data); // <--- AÑADE ESTO
        this.dataSource = data;
      },
      error: (err) => console.error('❌ Error al cargar:', err)
    });
  }

  // 3. Método ÚNICO para Abrir Diálogo (Sirve para CREAR y EDITAR)
  // Tu HTML llama a este método tanto en el botón de arriba como en el lápiz
  abrirDialogo(doctorEditar?: Doctor) {
    const dialogRef = this.dialog.open(DoctorDialogComponent, {
      width: '400px',
      data: doctorEditar || null // Si pasamos datos es Editar, si es null es Crear
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        if (doctorEditar) {
          // MODO EDITAR
          this.doctorService.actualizarDoctor(doctorEditar.id, result).subscribe({
            next: () => {
              console.log('Doctor actualizado');
              this.cargarDoctores();
            },
            error: () => alert('Error al actualizar doctor.')
          });
        } else {
          // MODO CREAR
          this.doctorService.crearDoctor(result).subscribe({
            next: () => {
              console.log('Doctor creado');
              this.cargarDoctores();
            },
            error: () => alert('Error al crear doctor. ¿Email repetido?')
          });
        }
      }
    });
  }

  // 4. Método para Eliminar
  eliminarDoctor(id: number) {
    if (confirm('¿Seguro que quieres eliminar a este doctor?')) {
      this.doctorService.eliminarDoctor(id).subscribe({
        next: () => {
          console.log('Doctor eliminado');
          this.cargarDoctores();
        },
        error: () => alert('No se pudo eliminar.')
      });
    }
  }
}
