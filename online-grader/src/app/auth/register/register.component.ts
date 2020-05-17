import { Component, OnInit } from "@angular/core";
import { AuthService } from "src/app/services/auth.service";
import { Router } from "@angular/router";
import { UserI } from "../../models/user";

@Component({
  selector: "app-register",
  templateUrl: "./register.component.html",
  styleUrls: ["./register.component.css"]
})
export class RegisterComponent implements OnInit {
  constructor(private authService: AuthService, private router: Router) {}

  ngOnInit() {}

  onRegister(form): void {
    console.log(form.value);
    this.authService.register(form.value).subscribe(res => {
      this.router.navigateByUrl("./auth");
    });
  }
}
