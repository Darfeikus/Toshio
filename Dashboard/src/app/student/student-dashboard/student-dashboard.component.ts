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
  myAssignments: any = [];
  mySubmissions: any = [];
  inactive: any = [];
  myGroups: any = [];

  constructor(private http: HttpClient, private router: Router) { }

  ngOnInit(): void {
    this.http.get('http://localhost:8000/api/group/student/A01732313')
      .subscribe(res => {
        (res as any).forEach(grupo => {
          this.myGroups.push(grupo);
        });
        // console.log(this.myGroups);
      })

    this.http.get('http://localhost:8000/api/assignment/student/A01732313')
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          if (!assignment.active) {
            this.inactive.push(assignment);
          }
        });
      })
    this.http.get('http://localhost:8000/api/submission/A01732313')
      .subscribe(res => {
        this.mySubmissions = res;
        this.myAssignments.forEach(assignment => {
          this.mySubmissions.forEach(submission => {
            if(assignment.assignment_id == submission.assignment_id){
              if (assignment.tries == submission.tries_left) {
                assignment['status'] = !submission.grade ? 'Not delivered':'0/100';
              }
              else {
                assignment['status'] = submission.grade + '/100';
              }
            }
          });
        });
        // console.log(this.myAssignments);
      })
  }

  overdue(end_date){
    return new Date(end_date) < new Date();
  }

  searchGroup(crn) {
    for (var i = 0; i < this.myGroups.length; i++) {
      if (this.myGroups[i]['crn'].toString() == crn) {
        return this.myGroups[i]['name'];
      }
    }
  }

  date(date) {
    return date.substring(0, 19);
  }

  uploadAttempt(assignment_id): void {
    this.router.navigateByUrl("/student/attempt?assignment_id=" + assignment_id);
  }
}
