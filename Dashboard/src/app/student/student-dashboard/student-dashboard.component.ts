import { Component, OnInit } from "@angular/core";
import {
  NgbModal,
  ModalDismissReasons,
  NgbDate,
  NgbCalendar,
} from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { NgbDateParserFormatter } from '@ng-bootstrap/ng-bootstrap'

@Component({
  selector: "app-student-dashboard",
  templateUrl: "./student-dashboard.component.html",
  styleUrls: ["./student-dashboard.component.css"],
})

export class StudentDashboardComponent implements OnInit {
  myAssignemnts: any = [];
  myGroups: any = [];

  constructor(private http: HttpClient,private router: Router) {}

  ngOnInit(): void {
    this.http.get('http://localhost:8000/api/group/student/A01732313')
      .subscribe(res => {
        (res as any).forEach(grupo => {
          this.myGroups.push(grupo);
        });
        console.log(this.myGroups);
      })

    this.http.get('http://localhost:8000/api/assignment/student/A01732313')
      .subscribe(res => {
        this.myAssignemnts = res;
        console.log(this.myAssignemnts);
      })
  }

  searchGroup(crn) {
    for (var i = 0; i < this.myGroups.length; i++) {
      if (this.myGroups[i]['crn'].toString() == crn) {
        return this.myGroups[i]['name'];
      }
    }
  }

  uploadAttempt(assignment_id): void {
    this.router.navigateByUrl("/student/attempt?assignment_id="+assignment_id);
    
  }
}
