import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { HomeComponent } from "./dashboard/home/home.component";

const routes: Routes = [
  { path: "", redirectTo: "/dashboard/home", pathMatch: "full" },
  { path: "auth", loadChildren: "./auth/auth.module#AuthModule" },
  {
    path: "dashboard",
    loadChildren: "./dashboard/dashboard.module#DashboardModule"
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
