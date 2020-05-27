import { Component, OnInit } from "@angular/core";
import {
  NgbModal,
  ModalDismissReasons,
  NgbDate,
  NgbCalendar,
} from "@ng-bootstrap/ng-bootstrap";
import { Router } from "@angular/router";

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

  constructor(
    private modalService: NgbModal,
    private calendar: NgbCalendar,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.setCurrentTimeForStartingTime();
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
    this.dateClose = this.calendar.getNext(this.dateOpen, "d", 1);
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
