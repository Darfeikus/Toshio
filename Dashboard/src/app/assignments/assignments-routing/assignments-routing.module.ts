import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AssignmentsComponent } from "../assignments.component";
import { AssignmentDetailsComponent } from "../assignment-details/assignment-details.component";
const routes: Routes = [
  {
    path: "",
    pathMatch: "full",
    component: AssignmentsComponent,
    data: {
      title: "Assignments",
    },
  },
  {
    path: "details",
    pathMatch: "full",
    component: AssignmentDetailsComponent,
    data: {
      title: "Code",
    },
  },
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AssignmentsRoutingModule {}
