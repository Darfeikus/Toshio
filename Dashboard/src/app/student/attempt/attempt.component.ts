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
    fileSource: new FormControl('', [Validators.required])
  });

  pdfSrc = "";
  assignment_id = 0;
  assignmentinfo: any = [];

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
    this.http.post('http://localhost:8000/api/submission?id=A01732313&idAss=' + this.assignment_id + '&lang=' + this.assignmentinfo.language + '&crn=' + this.assignmentinfo.crn, formData)
      .subscribe(res => {
        console.log(res[0]);
        alert(res[0]);
        location.reload();
      })
  }

  ngOnInit(): void {
    this.http.get('http://localhost:8000/api/assignment/' + this.assignment_id).subscribe(res => {
      this.assignmentinfo = res;
      // this.pdfSrc = "http://localhost:8000/public/rules.pdf";
      
    });
  }

  getDayOfWeek(d) {
    const date = new Date(d);
    const day = isNaN(date.getDay()) ? null :
      ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'][date.getDay()];
    return day + " " + date.getDay().toString() + " de " + this.mes(date.getMonth()) + " de " + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes();
  }

  mes(num) {
    return ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][num];
  }

}
