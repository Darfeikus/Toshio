import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { StudentRoutingModule } from './routing/student-routing.module';
import { StudentDashboardComponent } from './student-dashboard/student-dashboard.component';



@NgModule({
  declarations: [StudentDashboardComponent],
  imports: [
    CommonModule,
    StudentRoutingModule
  ]
})
export class StudentModule { }
