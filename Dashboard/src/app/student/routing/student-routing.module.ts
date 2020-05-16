import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { StudentDashboardComponent } from '../student-dashboard/student-dashboard.component';
import { AttemptComponent } from '../attempt/attempt.component';


const routes: Routes = [
  {
    path: "",
    pathMatch: "full",
    component: StudentDashboardComponent,
    data: {
      title: "Dashboard"
    }
  },
  {
    path: "attempt",
    pathMatch: "full",
    component: AttemptComponent,
    data: {
      title: "Attempt"
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class StudentRoutingModule { }
