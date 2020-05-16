import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { StudentDashboardComponent } from '../student-dashboard/student-dashboard.component';


const routes: Routes = [
  {
    path: "",
    pathMatch: "full",
    component: StudentDashboardComponent,
    data: {
      title: "Dashboard"
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class StudentRoutingModule { }
