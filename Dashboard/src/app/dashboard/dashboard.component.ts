import { Component, OnInit } from "@angular/core";
import { NgbModal, ModalDismissReasons } from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { HttpClient } from '@angular/common/http';
import { RequestsService } from './../shared/services/requests.service';

@Component({
  selector: "app-dashboard",
  templateUrl: "./dashboard.component.html",
  styleUrls: ["./dashboard.component.scss"],
})
export class DashboardComponent implements OnInit {
  activeHomeworks: any = [];
  inactiveHomeworks: any = [];
  myGroups: any = [];
  myAssignments: any = [];
  submissions: any = [];
  inactive: any = [];
  languages: object;
  closeResult = "";

  constructor(private modalService: NgbModal, private http: HttpClient, private router: Router, private requestsService: RequestsService, ) { }

  ngOnInit(): void {
    this.requestsService.get("submission")
      .subscribe(res => {
        this.submissions = res;
      });
    this.requestsService.get("group/teacher/" + localStorage.getItem('id'))
      .subscribe(res => {
        this.myGroups = res;
      });
    this.requestsService.get("assignment/teacher/"+localStorage.getItem('id'))
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          if (!assignment.active) {
            this.inactive.push(assignment);
          }
        });
        this.myAssignments = this.myAssignments.filter(assignment => assignment.active);
      });
      

    // llamar a API y llenar arreglos para hacer dinámico el listado
  }

  open(content) {
    this.modalService
      .open(content, { ariaLabelledBy: "modal-basic-title" })
      .result.then(
        (result) => {
          this.closeResult = `Closed with: ${result}`;
        },
        (reason) => {
          this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
        }
      );
  }

  details(assignment_id) {
    this.router.navigateByUrl("/groups/details?assignment_id=" + assignment_id);
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return "by pressing ESC";
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return "by clicking on a backdrop";
    } else {
      return `with: ${reason}`;
    }
  }
}
