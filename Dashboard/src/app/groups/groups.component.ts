import { Component, OnInit } from "@angular/core";
import { NgbModal, ModalDismissReasons } from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { RequestsService } from './../shared/services/requests.service';

@Component({
  selector: "app-groups",
  templateUrl: "./groups.component.html",
  styleUrls: ["./groups.component.css"],
})
export class GroupsComponent implements OnInit {
  myGroups: object;

  myForm = new FormGroup({
    name: new FormControl('',[Validators.required]),
    file: new FormControl(''),
    fileSource: new FormControl('', [Validators.required])
  });

  constructor(private http: HttpClient, private modalService: NgbModal, private router: Router, private requestsService: RequestsService) {}
  
  ngOnInit(): void {
    this.requestsService.get("group/teacher/"+localStorage.getItem('id'))
      .subscribe(res => {
        this.myGroups = res;
      })
  }

  closeResult = "";

  get f() {
    return this.myForm.controls;
  }

  onFileChange(event) {

    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.myForm.patchValue({
        fileSource: file
      });
    }
  }

  submit() {
    const formData = new FormData();
    formData.append('file', this.myForm.get('fileSource').value);
    formData.append('name',this.myForm.get('name').value);
    formData.append('id',localStorage.getItem('id'));
    this.requestsService.post("group",formData)
      .subscribe(res => {
        console.log(res);
        if(res['error']){
          alert(res["message"]);
        }
        else{
          location.reload();
        }
      })
  }

  open(content) {
    this.modalService
      .open(content, { ariaLabelledBy: "modal-basic-title", size: "lg" })
      .result.then(
        (result) => {
          this.closeResult = `Closed with: ${result}`;
        },
        (reason) => {
          this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
        }
      );
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

  delete($crn){
    this.requestsService.get("group/delete/"+$crn)
      .subscribe(res => {
        if(res){
          location.reload();
        }
        else{
          alert("There was a problem while deleting")
        }
      });
  }

  details() {
    this.router.navigateByUrl("/groups/details");
  }

}
