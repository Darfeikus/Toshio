import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { LoginService } from './services/login.service';
import { HttpResponse } from '@angular/common/http';

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.css"],
})
export class LoginComponent implements OnInit {

  graderLoginForm = new FormGroup({
    studentId: new FormControl(''),
    password: new FormControl(''),
  });

  constructor(
    private router: Router,
    private loginService: LoginService
  ) { }

  ngOnInit(): void { }

  /*
  ! obviamente primero se valida, y si es exitoso, lo manda al dashboard con su token en la sesión
  */
  login() {
    this.loginService.makeLogin(this.graderLoginForm).
      subscribe((res: any) => {
        localStorage.setItem('token', res.token);
        localStorage.setItem('id', this.graderLoginForm.get('studentId').value);
        let role = this.loginService.getRole(res.token);
        switch (role) {
          case "student":
            this.router.navigateByUrl("/student");
          break;

          case "teacher":
            this.router.navigateByUrl("/");
          break;
        
          default:
            localStorage.removeItem("token");
            localStorage.removeItem('id');
          break;
        }
      }, err => {
        console.log(err);
      })
  }
}
