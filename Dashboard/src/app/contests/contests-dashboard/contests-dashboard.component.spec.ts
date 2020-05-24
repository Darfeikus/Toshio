import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ContestsDashboardComponent } from './contests-dashboard.component';

describe('ContestsDashboardComponent', () => {
  let component: ContestsDashboardComponent;
  let fixture: ComponentFixture<ContestsDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ContestsDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ContestsDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
