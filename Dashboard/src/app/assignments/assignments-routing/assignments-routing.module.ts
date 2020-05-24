import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AssignmentsComponent } from "../assignments.component";
const routes: Routes = [
  {
    path: "",
    pathMatch: "full",
    component: AssignmentsComponent,
    data: {
      title: "Assignments",
    },
  },
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AssignmentsRoutingModule {}
