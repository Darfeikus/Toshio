import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { StudentRoutingModule } from './routing/student-routing.module';
import { StudentDashboardComponent } from './student-dashboard/student-dashboard.component';
import { AttemptComponent } from './attempt/attempt.component';



@NgModule({
  declarations: [StudentDashboardComponent, AttemptComponent],
  imports: [
    CommonModule,
    StudentRoutingModule
  ]
})
export class StudentModule { }
