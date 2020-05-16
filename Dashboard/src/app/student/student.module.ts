import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { StudentRoutingModule } from './routing/student-routing.module';
import { StudentDashboardComponent } from './student-dashboard/student-dashboard.component';
import { AttemptComponent } from './attempt/attempt.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';


@NgModule({
  declarations: [StudentDashboardComponent, AttemptComponent],
  imports: [
    CommonModule,
    StudentRoutingModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [],
  bootstrap:[AttemptComponent]
})
export class StudentModule { }
