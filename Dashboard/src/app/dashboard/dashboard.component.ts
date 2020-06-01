import { Component, OnInit } from "@angular/core";
import { NgbModal, ModalDismissReasons } from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { HttpClient } from '@angular/common/http';

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
  
  constructor(private modalService: NgbModal, private http: HttpClient, private router: Router) {}

  ngOnInit(): void {

    this.http.get('http://localhost:8000/api/submission')
      .subscribe(res => {
        this.submissions = res;
        // console.log(this.myGroups);
      });

    this.http.get('http://localhost:8000/api/group/teacher/A01329173')
      .subscribe(res => {
        this.myGroups = res;
        // console.log(this.myGroups);
      });

    this.http.get('http://localhost:8000/api/assignment/teacher/A01329173')
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          if(!assignment.active){
            this.inactive.push(assignment);
          }
        });
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

  details(assignment_id){
    this.router.navigateByUrl("/groups/details?assignment_id="+assignment_id);
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
