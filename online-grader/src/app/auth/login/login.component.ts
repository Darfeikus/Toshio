import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";
import { UserI } from "../../models/user";
import { AuthService } from "src/app/services/auth.service";

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.css"]
})
export class LoginComponent implements OnInit {
  constructor(private authService: AuthService, private router: Router) {}

  ngOnInit() {}

  onLogin(form): void {
    this.authService.login(form.value).subscribe(res => {
      // mandar a dashboard, no a auth
      this.router.navigateByUrl("/auth");
    });
  }
}
