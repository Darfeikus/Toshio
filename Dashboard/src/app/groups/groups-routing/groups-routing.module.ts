import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { GroupsComponent } from "../groups.component";
import { GroupsDetailsComponent } from "../groups-details/groups-details.component";
const routes: Routes = [
  {
    path: "",
    pathMatch: "full",
    component: GroupsComponent,
    data: {
      title: "Groups",
    },
  },
  {
    path: "details",
    pathMatch: "full",
    component: GroupsDetailsComponent,
    data: {
      title: "Details",
    },
  },
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class GroupsRoutingModule {}
