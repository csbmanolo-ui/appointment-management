import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CitaDialogComponent } from './cita-dialog.component';

describe('CitaDialog', () => {
  let component: CitaDialogComponent;
  let fixture: ComponentFixture<CitaDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CitaDialogComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CitaDialogComponent );
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
