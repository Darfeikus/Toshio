import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.css"],
})
export class LoginComponent implements OnInit {
  constructor(private router: Router) {}

  ngOnInit(): void {}

  /*
  ! obviamente primero se valida, y si es exitoso, lo manda al dashboard con su token en la sesión
  */
  dash() {
    this.router.navigateByUrl("/");
  }
}
