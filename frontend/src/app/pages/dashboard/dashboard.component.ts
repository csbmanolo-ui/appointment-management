import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';
import { MatIconModule } from '@angular/material/icon';
import { DashboardService } from '../../services/dashboard.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, MatCardModule, MatIconModule],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {

  private dashboardService = inject(DashboardService);

  // Variables para guardar los datos
  stats: any = {
    total_pacientes: 0,
    total_doctores: 0,
    citas_hoy_count: 0,
    citas_hoy: []
  };

  ngOnInit() {
    this.cargarDatos();
  }

  cargarDatos() {
    this.dashboardService.getStats().subscribe({
      next: (data) => {
        this.stats = data;
      },
      error: (err) => console.error('Error cargando dashboard:', err)
    });
  }
}
