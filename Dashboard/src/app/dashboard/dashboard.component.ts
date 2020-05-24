import { Component, OnInit } from "@angular/core";
@Component({
  selector: "app-dashboard",
  templateUrl: "./dashboard.component.html",
  styleUrls: ["./dashboard.component.scss"],
})
export class DashboardComponent implements OnInit {
  activeHomeworks: any = [];
  inactiveHomeworks: any = [];
  groups: any = [];

  constructor() {}

  ngOnInit() {
    // llamar a API y llenar arreglos para hacer dinámico el listado
  }
}
