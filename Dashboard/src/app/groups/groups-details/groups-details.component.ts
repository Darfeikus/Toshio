import { Component, OnInit } from "@angular/core";
import { NgbModal, ModalDismissReasons } from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { ActivatedRoute, Params } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { AppService } from './app.service';
import { RequestsService } from '../../shared/services/requests.service';

@Component({
  selector: 'app-groups-details',
  templateUrl: './groups-details.component.html',
  styleUrls: ['./groups-details.component.css']
})

export class GroupsDetailsComponent implements OnInit {
  
  assignment: any;
  assignment_id = 0;
  submissions: any;

  constructor(private http: HttpClient, private activatedRoute: ActivatedRoute,  private router: Router, private appService:AppService, private requestsService: RequestsService,) {
    this.activatedRoute.queryParams.subscribe((params: Params) => {
      this.assignment_id = params.assignment_id;
    });
  }

  ngOnInit(): void {
    this.requestsService.get("assignment/"+this.assignment_id)
      .subscribe(res => {
        this.assignment = res;
      });
      this.requestsService.get('submission/assignment/'+this.assignment_id)
      .subscribe(res => {
        this.submissions = res;
      });
    }

  download(){
    this.appService.downloadFile(this.submissions, this.assignment_id+"_"+this.assignment[0].name);
  }
}
