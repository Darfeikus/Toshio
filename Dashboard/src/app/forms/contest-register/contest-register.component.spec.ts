import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ContestRegisterComponent } from './contest-register.component';

describe('ContestRegisterComponent', () => {
  let component: ContestRegisterComponent;
  let fixture: ComponentFixture<ContestRegisterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ContestRegisterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ContestRegisterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
