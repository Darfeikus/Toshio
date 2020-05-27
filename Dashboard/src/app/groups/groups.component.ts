import { Component, OnInit } from "@angular/core";
import { NgbModal, ModalDismissReasons } from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators } from '@angular/forms';

@Component({
  selector: "app-groups",
  templateUrl: "./groups.component.html",
  styleUrls: ["./groups.component.css"],
})
export class GroupsComponent implements OnInit {
  myForm = new FormGroup({
    name: new FormControl(''),
    file: new FormControl('', [Validators.required]),
    fileSource: new FormControl('', [Validators.required])
  });

  closeResult = "";
  constructor(private http: HttpClient, private modalService: NgbModal, private router: Router) {}

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

    this.http.post('http://localhost:8000/api/group?id=A01329173', formData,{responseType: 'text'})
      .subscribe(res => {
        console.log(res);
        alert('Uploaded Successfully.');
        location.reload()
      })
  }

  ngOnInit(): void {}

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

  details() {
    this.router.navigateByUrl("/groups/details");
  }
  
}
