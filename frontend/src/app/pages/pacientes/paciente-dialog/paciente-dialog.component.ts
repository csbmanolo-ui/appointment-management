import { Component, inject, Inject, OnInit } from '@angular/core'; // Añadido Inject, OnInit
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MatDialogModule, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatSelectModule } from '@angular/material/select';
import { Paciente } from '../../../models/paciente.model';

@Component({
  selector: 'app-paciente-dialog',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, MatDialogModule,
    MatFormFieldModule, MatInputModule, MatButtonModule, MatSelectModule
  ],
  templateUrl: './paciente-dialog.component.html',
  styleUrls: ['./paciente-dialog.component.css']
})
export class PacienteDialogComponent implements OnInit {
  private fb = inject(FormBuilder);
  private dialogRef = inject(MatDialogRef<PacienteDialogComponent>);


  constructor(@Inject(MAT_DIALOG_DATA) public data: Paciente | null) {}

  pacienteForm: FormGroup = this.fb.group({
    nombre: ['', Validators.required],
    apellidos: ['', Validators.required],
    email: ['', [Validators.required, Validators.email]],
    telefono: ['', Validators.required],
    seguro: ['Privado', Validators.required]
  });

  ngOnInit(): void {
    if (this.data) {
      this.pacienteForm.patchValue(this.data);
    }
  }

  guardar() {
    if (this.pacienteForm.valid) {
      const resultado = {
        ...this.pacienteForm.value,
        id: this.data ? this.data.id : null
      };
      this.dialogRef.close(resultado);
    }
  }

  cancelar() {
    this.dialogRef.close();
  }
}
