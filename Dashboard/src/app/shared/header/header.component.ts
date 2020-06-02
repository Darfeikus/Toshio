import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";
import { RequestsService } from './../../shared/services/requests.service';

@Component({
  selector: "app-header",
  templateUrl: "./header.component.html",
  styleUrls: ["./header.component.scss"],
})
export class HeaderComponent implements OnInit {
  constructor(private router: Router, private requestsService: RequestsService) { }

  ngOnInit() {
  }

  cerrarSesion() {
    localStorage.clear();
    // mandar a login
    this.router.navigateByUrl("/login");
  }
}
