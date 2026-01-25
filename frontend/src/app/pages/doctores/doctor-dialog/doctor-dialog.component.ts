import { Component, inject, Inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MatDialogModule, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { Doctor } from '../../../models/doctor.model';

@Component({
  selector: 'app-doctor-dialog',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, MatDialogModule,
    MatFormFieldModule, MatInputModule, MatButtonModule
  ],
  templateUrl: './doctor-dialog.component.html',
  styleUrls: ['./doctor-dialog.component.css']
})
export class DoctorDialogComponent implements OnInit {
  private fb = inject(FormBuilder);
  private dialogRef = inject(MatDialogRef<DoctorDialogComponent>);

  constructor(@Inject(MAT_DIALOG_DATA) public data: Doctor | null) {}

  doctorForm: FormGroup = this.fb.group({
    nombre: ['', Validators.required],
    apellidos: ['', Validators.required],
    especialidad: ['', Validators.required], // Campo nuevo
    email: ['', [Validators.required, Validators.email]],
    telefono: ['', Validators.required]
  });

  ngOnInit(): void {
    if (this.data) {
      this.doctorForm.patchValue(this.data);
    }
  }

  guardar() {
    if (this.doctorForm.valid) {
      this.dialogRef.close(this.doctorForm.value);
    }
  }

  cancelar() {
    this.dialogRef.close();
  }
}
