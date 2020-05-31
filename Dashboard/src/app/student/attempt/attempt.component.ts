import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ActivatedRoute, Params } from '@angular/router';

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

  constructor(private http: HttpClient, private activatedRoute: ActivatedRoute) {
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

  ngOnInit(): void {
    this.http.get('http://localhost:8000/api/assignment/' + this.assignment_id).subscribe(res => {
      this.assignmentinfo = res[0];
    });
    this.http.get('http://localhost:8000/api/submission/A01732313')
      .subscribe(res => {
        this.mySubmissions = res;
        this.mySubmissions.forEach(submission => {
          if (this.assignmentinfo.assignment_id == submission.assignment_id) {
            if (this.assignmentinfo.tries == submission.tries_left) {
              this.assignmentinfo['status'] = 'Not delivered';
            }
            else {
              this.assignmentinfo['status'] = submission.grade + '/100';
            }
          }
        });
        console.log(this.assignmentinfo);
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
