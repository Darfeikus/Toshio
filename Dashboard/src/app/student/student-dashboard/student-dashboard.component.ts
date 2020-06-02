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
import { RequestsService } from './../../shared/services/requests.service';

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

  constructor(private http: HttpClient, private router: Router, private requestsService: RequestsService) { }

  ngOnInit(): void {

    this.requestsService.get("assignment/student/"+localStorage.getItem('id'))
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          assignment['status'] = 'Sin entregar';
          if (!assignment.active) {
            this.inactive.push(assignment);
          }
        });
        console.log(this.myAssignments);
      })
    this.requestsService.get('submission/'+localStorage.getItem('id'))
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

  getId(){
    return localStorage.getItem('id');
  }
}
