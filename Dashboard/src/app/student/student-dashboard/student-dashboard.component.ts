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
import { JsonPipe } from '@angular/common';

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
    this.http.get('http://localhost:8000/api/assignment/student/A01732313')
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          assignment['status'] = 'Sin entregar';
          if (!assignment.active) {
            this.inactive.push(assignment);
          }
        });
      })
    this.http.get('http://localhost:8000/api/submission/A01732313')
      .subscribe(res => {
        this.mySubmissions = res;
        this.mySubmissions.forEach(submission => {
          var index = this.myAssignments.findIndex(x => x.assignment_id == submission.assignment_id);
          this.myAssignments[index]['status'] = submission.grade+"/100";
        });
      })
  }

  overdue(end_date) {
    return new Date(end_date) < new Date();
  }

  date(date) {
    return date.substring(0, 19);
  }

  uploadAttempt(assignment_id): void {
    this.router.navigateByUrl("/student/attempt?assignment_id=" + assignment_id);
  }
}
