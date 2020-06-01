import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ActivatedRoute, Params } from '@angular/router';
import { Router } from "@angular/router";

@Component({
  selector: 'app-attempt',
  templateUrl: './attempt.component.html',
  styleUrls: ['./attempt.component.css'],
  template: `
  <pdf-viewer [src]="pdfSrc" 
              [render-text]="true"
              style="display: block;"
  ></pdf-viewer>
  `
})
export class AttemptComponent implements OnInit {

  myForm = new FormGroup({
    file: new FormControl(''),
    fileSource: new FormControl('', [Validators.required]),
  });

  pdfSrc = "";
  assignment_id = 0;
  assignmentinfo: any = [];
  len = "";
  extension = "";
  mySubmissions: any = [];
  labels: string[] = [];

  constructor(private http: HttpClient, private activatedRoute: ActivatedRoute,  private router: Router) {
    this.activatedRoute.queryParams.subscribe((params: Params) => {
      this.assignment_id = params.assignment_id;
    });
  }

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
    formData.append('assignment_id', this.assignment_id.toString());
    formData.append('lang', this.assignmentinfo.extension);
    formData.append('crn', this.assignmentinfo.crn);
    formData.append('runtime', this.assignmentinfo.runtime);

    this.http.post('http://localhost:8000/api/submission?id=A01732313', formData)
      .subscribe(res => {
        console.log(res);
        if (res['error']) {
          alert(res["message"]);
          this.assignmentinfo['status'] = '0.00';
        }
        else {
          for (let index = 0; index < Object.keys(res).length; index++) {
            this.labels.push(res[index]);
          }
        }
      }, err => {
        console.log(err);
        if (err['status'] == 413) {
          alert('File too large');
        }
      }
      );
  }

  indexOfObj(array, attr, value) {
    for(var i = 0; i < array.length; i++) {
        if(array[i][attr] === value) {
            return i;
        }
    }
    return -1;
  }

  ngOnInit(): void {
    const date = new Date();
    
    this.http.get('http://localhost:8000/api/submission/assignment/' + this.assignment_id)
      .subscribe(res => {
        var index = this.indexOfObj(res,'id','A01732313');
        if(index<0){
          this.router.navigateByUrl("/student");
        }
      });

    this.http.get('http://localhost:8000/api/assignment/' + this.assignment_id).subscribe(res => {
      this.assignmentinfo = res[0];
      if(new Date(this.assignmentinfo.start_date) > date || date > new Date(this.assignmentinfo.end_date)){
        this.router.navigateByUrl("/student");
      }
      var index = this.mySubmissions.findIndex(x => x.assignment_id == this.assignmentinfo.assignment_id);
      if(index != -1){
        this.assignmentinfo['status'] = this.mySubmissions[index].grade + "/100";
      }
      else{
        this.assignmentinfo['status'] = "Sin entregar";
      }
    });
  }

  getDayOfWeek(d) {
    const date = new Date(d);
    const day = isNaN(date.getDay()) ? null :
      ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'][date.getDay()];
    return day + " " + date.getDate() + " de " + this.mes(date.getMonth()) + " de " + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes();
  }

  mes(num) {
    return ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][num];
  }

}
