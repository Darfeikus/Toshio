import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ConcursosComponent } from './concursos.component';

describe('ConcursosComponent', () => {
  let component: ConcursosComponent;
  let fixture: ComponentFixture<ConcursosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ConcursosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ConcursosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
