import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FullCalendarModule } from '@fullcalendar/angular';
import { CalendarOptions, EventInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import { MatDialog, MatDialogModule } from '@angular/material/dialog';

import { CitaService } from '../../services/cita.service';
import { CitaDialogComponent } from './cita-dialog/cita-dialog.component';

@Component({
  selector: 'app-calendario',
  standalone: true,
  imports: [CommonModule, FullCalendarModule, MatDialogModule],
  templateUrl: './calendario.component.html',
  styleUrls: ['./calendario.component.css']
})
export class CalendarioComponent implements OnInit {

  private citaService = inject(CitaService);
  private dialog = inject(MatDialog);

  calendarOptions: CalendarOptions = {
    initialView: 'timeGridWeek',
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    locale: esLocale,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    slotMinTime: '08:00:00',
    slotMaxTime: '21:00:00',
    allDaySlot: false,
    events: [],

    // 1. CLIC EN HUECO VACÍO -> CREAR
    dateClick: (arg) => this.handleDateClick(arg),

    // 2. CLIC EN CITA EXISTENTE -> EDITAR
    eventClick: (info) => this.handleEventClick(info)
  };

  ngOnInit() {
    this.cargarCitas();
  }

  cargarCitas() {
    this.citaService.getCitas().subscribe({
      next: (citasBackend) => {
        const eventos: EventInput[] = citasBackend.map(cita => {
          const nombrePaciente = cita.paciente
            ? `${cita.paciente.nombre} ${cita.paciente.apellidos}`
            : 'Paciente Desconocido';

          return {
            id: cita.id?.toString(),
            title: `${nombrePaciente}\n(${cita.motivo_consulta})`,
            start: cita.fecha_hora_inicio,
            end: cita.fecha_hora_fin,
            backgroundColor: cita.doctor?.color || '#3788d8',
            borderColor: 'transparent',
            textColor: '#ffffff',

            // GUARDAMOS EL OBJETO ORIGINAL AQUÍ PARA RECUPERARLO LUEGO
            extendedProps: { citaOriginal: cita }
          };
        });
        this.calendarOptions = { ...this.calendarOptions, events: eventos };
      },
      error: (err) => console.error('Error al cargar citas:', err)
    });
  }

  // --- CREAR ---
  handleDateClick(arg: any) {
    const dialogRef = this.dialog.open(CitaDialogComponent, {
      width: '500px',
      data: { startStr: arg.dateStr, start: arg.date }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.citaService.crearCita(result).subscribe(() => this.cargarCitas());
      }
    });
  }

  // --- EDITAR / ELIMINAR ---
  handleEventClick(info: any) {
    // Recuperamos la cita original desde 'extendedProps'
    const cita = info.event.extendedProps.citaOriginal;

    const dialogRef = this.dialog.open(CitaDialogComponent, {
      width: '500px',
      data: { cita: cita } // Pasamos la cita entera para editar
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        if (result === 'eliminar') {
           if(confirm('¿Borrar cita?')) {
             this.citaService.eliminarCita(cita.id).subscribe(() => this.cargarCitas());
           }
        } else {
           // MODO EDICIÓN REAL
           this.citaService.actualizarCita(cita.id, result).subscribe(() => this.cargarCitas());
        }
      }
    });
  }
}
