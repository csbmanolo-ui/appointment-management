import { TestBed } from '@angular/core/testing';

import { Paciente } from './paciente.service';

describe('Paciente', () => {
  let service: Paciente;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(Paciente);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
