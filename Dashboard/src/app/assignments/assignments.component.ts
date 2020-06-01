import { Component, OnInit } from "@angular/core";
import {
  NgbModal,
  ModalDismissReasons,
  NgbDate,
  NgbCalendar,
} from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";
import { HttpClient } from '@angular/common/http';
import { FormGroup, FormControl, Validators, FormsModule } from '@angular/forms';
import { NgbDateParserFormatter } from '@ng-bootstrap/ng-bootstrap'
import { RequestsService } from './../shared/services/requests.service';

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
  currentAssignment: any = [];
  
  constructor(
    private parserFormatter: NgbDateParserFormatter,
    private http: HttpClient,
    private modalService: NgbModal,
    private calendar: NgbCalendar,
    private router: Router,
    private requestsService: RequestsService,
  ) { }

  ngOnInit(): void {
    this.setCurrentTimeForStartingTime();
    this.requestsService.get("group/teacher/"+localStorage.getItem('id'))
      .subscribe(res => {
        this.myGroups = res;
      }, this.requestsService.unauthorized);
    this.requestsService.get("language")
      .subscribe(res => {
        this.languages = res;
      }, this.requestsService.unauthorized);
    this.requestsService.get("assignment/teacher/"+localStorage.getItem('id'))
      .subscribe(res => {
        this.myAssignments = res;
        this.myAssignments.forEach(assignment => {
          if (!assignment.active) {
            this.inactive.push(assignment);
          }
        });
      }, this.requestsService.unauthorized);
  }

  myForm = new FormGroup({
    nombre: new FormControl('', [Validators.required]),
    materia: new FormControl(''),
    lenguaje: new FormControl(''),
    intentos: new FormControl('', [Validators.required, Validators.min(0)]),
    runtime: new FormControl('', [Validators.required, Validators.min(0.00), Validators.max(99.99)]),
    fechaApertura: new FormControl('', [Validators.required]),
    fechaClausura: new FormControl('', [Validators.required]),
    horaApertura: new FormControl(''),
    horaClausura: new FormControl(''),
    file: new FormControl(''),
    file2: new FormControl(''),
    fileSource: new FormControl(''),
    fileSource2: new FormControl('')
  });

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
    else {
      this.myForm.patchValue({ fileSource: null });
    }
  }

  onFileChange2(event) {
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.myForm.patchValue({
        fileSource2: file
      });
    }
    else {
      this.myForm.patchValue({ fileSource2: null });
    }
  }

  setTwoNumberDecimal(el) {
    el.value = parseFloat(el.value).toFixed(2);
  };

  submit() {
    if (this.myForm.valid) {
      const formData = new FormData();
      if (this.myForm.get('fileSource').value && this.myForm.get('fileSource2').value) {
        formData.append('file', this.myForm.get('fileSource').value);
        formData.append('file2', this.myForm.get('fileSource2').value);
      }
      else {
        alert("Faltan archivos.")
        return;
      }
      if (this.myForm.get('materia').value && this.myForm.get('lenguaje').value) {
        formData.append('materia', this.myForm.get('materia').value);
        formData.append('lenguaje', this.myForm.get('lenguaje').value);
      }
      else {
        alert("Faltan datos.")
        return;
      }
      formData.append('nombre', this.myForm.get('nombre').value);
      formData.append('materia', this.myForm.get('materia').value);
      formData.append('intentos', this.myForm.get('intentos').value);
      formData.append('runtime', this.myForm.get('runtime').value);
      formData.append('lenguaje', this.myForm.get('lenguaje').value);
      formData.append('fechaApertura', this.parserFormatter.format(this.dateOpen));
      formData.append('fechaClausura', this.parserFormatter.format(this.dateClose));
      formData.append('horaApertura', this.time.hour.toString().concat(":", this.time.minute.toString(), ":00"));
      formData.append('horaClausura', this.timeEnd.hour.toString().concat(":", this.timeEnd.minute.toString(), ":00"));

      this.requestsService.post("assignment",formData)
        .subscribe(res => {
          console.log(res);
          if (res['error']) {
            alert(res["message"]);
          }
          else {
            location.reload();
          }
        }, this.requestsService.unauthorized)
    }
  }

  update(assignment_id) {
    if (this.myForm.valid) {
      const formData = new FormData();
      if (this.myForm.get('fileSource').value) {
        formData.append('file', this.myForm.get('fileSource').value);
        console.log("nuevo ZIP");
      }
      else{
        formData.delete('file');
      }
      if (this.myForm.get('fileSource2').value) {
        formData.append('file2', this.myForm.get('fileSource2').value);
        console.log("nuevo PDF");
      }
      else{
        formData.delete('file2');
      }
      if (this.myForm.get('materia').value) {
        formData.append('materia', this.myForm.get('materia').value);
        console.log("nuevo grupo");
      }
      if (this.myForm.get('lenguaje').value) {
        formData.append('lenguaje', this.myForm.get('lenguaje').value);
        console.log("nuevo lenguaje");
      }
      formData.append('assignment_id', assignment_id);
      formData.append('nombre', this.myForm.get('nombre').value);
      formData.append('intentos', this.myForm.get('intentos').value);
      formData.append('runtime', this.myForm.get('runtime').value);
      formData.append('fechaApertura', this.parserFormatter.format(this.dateOpen));
      formData.append('fechaClausura', this.parserFormatter.format(this.dateClose));
      formData.append('horaApertura', this.time.hour.toString().concat(":", this.time.minute.toString(), ":00"));
      formData.append('horaClausura', this.timeEnd.hour.toString().concat(":", this.timeEnd.minute.toString(), ":00"));
      
      this.requestsService.post("assignment/update",formData)
        .subscribe(res => {
          res.toString();
          console.log(res);
          if (res['error']) {
            alert(res["message"]);
          }
          else {
            location.reload();
          }
        }, (error) => {
          this.requestsService.unauthorized(error);
          location.reload();
        });
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

  selectDateOpen(date: Date) {
    this.dateOpen = new NgbDate(date.getFullYear(), date.getMonth() + 1, date.getDate());
    this.time = { hour: date.getHours(), minute: date.getMinutes() };
  }

  selectDateClose(date: Date) {
    this.dateClose = new NgbDate(date.getFullYear(), date.getMonth() + 1, date.getDate());
    this.timeEnd = { hour: date.getHours(), minute: date.getMinutes() };
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

  dateF(date) {
    return date.substring(0, 19);
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

  openC(content, assignment) {
    console.log(assignment.assignment_id);
    this.selectDateOpen(new Date(assignment.start_date));
    this.selectDateClose(new Date(assignment.end_date));
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

  delete($id) {
    this.requestsService.get("assignment/delete/"+$id)
      .subscribe(res => {
        console.log(res);
        if (res) {
          location.reload();
        }
        else {
          alert("There was a problem while deleting")
        }
      }, this.requestsService.unauthorized);
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
