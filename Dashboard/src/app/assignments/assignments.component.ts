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
  selector: "app-assignments",
  templateUrl: "./assignments.component.html",
  styleUrls: ["./assignments.component.css"],
})
export class AssignmentsComponent implements OnInit {
  dateOpen: NgbDate;
  dateClose: NgbDate;
  closeResult = "";
  date: any;
  now = Date.now();
  time = { hour: 0, minute: 0 };
  timeEnd = { hour: 23, minute: 59 };
  myGroups: any;
  myAssignments: any = [];
  inactive: any = [];
  languages: object;

  ngOnInit(): void {
    this.setCurrentTimeForStartingTime();
    this.http.get('http://localhost:8000/api/group/teacher/A01329173')
      .subscribe(res => {
        this.myGroups = res;
        console.log(this.myGroups);
      })
    this.http.get('http://localhost:8000/api/language')
      .subscribe(res => {
        this.languages = res;
        console.log(this.languages);
      })
    this.http.get('http://localhost:8000/api/assignment/teacher/A01329173')
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          if(!assignment.active){
            this.inactive.push(assignment);
          }
        });
        console.log(this.myAssignments);
      })
  }

  searchGroup(crn) {
    for (var i = 0; i < this.myGroups.length; i++) {
      if (this.myGroups[i]['crn'].toString() == crn) {
        return this.myGroups[i]['name'];
      }
    }
  }

  myForm = new FormGroup({
    nombre: new FormControl('', [Validators.required]),
    materia: new FormControl('', [Validators.required]),
    intentos: new FormControl('', [Validators.required]),
    lenguaje: new FormControl('', [Validators.required]),
    fechaApertura: new FormControl('', [Validators.required]),
    fechaClausura: new FormControl('', [Validators.required]),
    horaApertura: new FormControl(''),
    horaClausura: new FormControl(''),
    file: new FormControl(''),
    file2: new FormControl(''),
    fileSource: new FormControl('', [Validators.required]),
    fileSource2: new FormControl('', [Validators.required])
  });

  constructor(
    private parserFormatter: NgbDateParserFormatter,
    private http: HttpClient,
    private modalService: NgbModal,
    private calendar: NgbCalendar,
    private router: Router
  ) { }

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

  onFileChange2(event) {
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.myForm.patchValue({
        fileSource2: file
      });
    }
  }

  submit() {
    if (this.myForm.valid) {
      const formData = new FormData();
      formData.append('file', this.myForm.get('fileSource').value);
      formData.append('file2', this.myForm.get('fileSource2').value);
      formData.append('nombre', this.myForm.get('nombre').value);
      formData.append('materia', this.myForm.get('materia').value);
      formData.append('intentos', this.myForm.get('intentos').value);
      formData.append('lenguaje', this.myForm.get('lenguaje').value);
      formData.append('fechaApertura', this.parserFormatter.format(this.dateOpen));
      formData.append('fechaClausura', this.parserFormatter.format(this.dateClose));
      formData.append('horaApertura', this.time.hour.toString().concat(":", this.time.minute.toString(), ":00"));
      formData.append('horaClausura', this.timeEnd.hour.toString().concat(":", this.timeEnd.minute.toString(), ":00"));

      this.http.post('http://localhost:8000/api/assignment?id=A0132973', formData, { responseType: 'text' })
        .subscribe(res => {
          console.log(res);
          var json = JSON.parse(res);
          if (json['error']) {
            alert(json["message"]);
          }
          else {
            location.reload();
          }
        })
    }
  }

  setCurrentTimeForStartingTime() {
    let date = new Date(this.now);
    let hours = date.getHours();
    let minutes = date.getMinutes();
    this.time.hour = hours;
    this.time.minute = minutes;
  }

  selectToday() {
    this.dateOpen = this.calendar.getToday();
  }

  oneDay() {
    this.dateClose = this.calendar.getNext(this.dateOpen, "d");
  }

  threeDays() {
    this.dateClose = this.calendar.getNext(this.dateOpen, "d", 3);
  }

  oneWeek() {
    this.dateClose = this.calendar.getNext(this.dateOpen, "d", 7);
  }

  details() {
    this.router.navigateByUrl("/assignments/details");
  }

  dateF(date){
    return date.substring(0,19);
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
}
