import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { BlankTemplateComponent } from "./template/blank-template.component";
import { LeftNavTemplateComponent } from "./template/left-nav-template.component";
import { PageNotFoundComponent } from "./page-not-found/page-not-found.component";

export const routes: Routes = [
  {
    path: "",
    component: LeftNavTemplateComponent,
    children: [
      {
        path: "",
        loadChildren: () =>
          import("./dashboard/dashboard.module").then((m) => m.DashboardModule),
      },
      {
        path: "ui-elements",
        loadChildren: () =>
          import("./ui-elements/ui-elements.module").then(
            (m) => m.UiElementsModule
          ),
      },
      {
        path: "assignments",
        loadChildren: () =>
          import("./assignments/assignments.module").then(
            (m) => m.AssignmentsModule
          ),
      },
      {
        path: "forms",
        loadChildren: () =>
          import("./forms/forms.module").then((m) => m.FormsModule),
        data: {
          title: "Form Page",
        },
      },
      {
        path: "student",
        loadChildren: () =>
          import("./student/student.module").then((m) => m.StudentModule),
      },
      {
        path: "students",
        loadChildren: () =>
          import("./students/students.module").then((m) => m.StudentsModule),
      },
      {
        path: "groups",
        loadChildren: () =>
          import("./groups/groups.module").then((m) => m.GroupsModule),
      },
    ],
  },
  {
    path: "tables",
    component: LeftNavTemplateComponent,
    data: {
      title: "Tables",
    },
    children: [
      {
        path: "",
        loadChildren: () =>
          import("./tables/tables.module").then((m) => m.TablesModule),
      },
    ],
  },
  {
    path: "**",
    component: PageNotFoundComponent,
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
  declarations: [],
})
export class AppRoutingModule {}
