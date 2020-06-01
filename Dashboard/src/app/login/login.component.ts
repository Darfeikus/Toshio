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
        this.router.navigateByUrl("/student");
      }, err => {
        console.log(err);
      })
  }
}
