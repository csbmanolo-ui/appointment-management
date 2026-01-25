import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Cita } from '../models/cita.model';

@Injectable({
  providedIn: 'root'
})
export class CitaService {
  private apiUrl = 'http://127.0.0.1:8000/api/citas';
  private http = inject(HttpClient);

  getCitas(): Observable<Cita[]> {
    return this.http.get<Cita[]>(this.apiUrl);
  }

  crearCita(cita: any): Observable<Cita> {
    return this.http.post<Cita>(this.apiUrl, cita);
  }

  eliminarCita(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }

  actualizarCita(id: number, cita: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, cita);
  }
}
