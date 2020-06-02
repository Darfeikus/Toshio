import { NgModule } from "@angular/core";
import { AlertModule } from "ngx-bootstrap/alert";
import { BsDropdownModule } from "ngx-bootstrap/dropdown";
import { TabsModule } from "ngx-bootstrap/tabs";
import { PaginationModule } from "ngx-bootstrap/pagination";
import { ChartsModule } from "ng2-charts";
import { AssignmentsComponent } from "./assignments.component";
import { AssignmentsRoutingModule } from "./assignments-routing/assignments-routing.module";
import { CommonModule } from "@angular/common";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { FormsModule, ReactiveFormsModule} from "@angular/forms";
import { AssignmentDetailsComponent } from "./assignment-details/assignment-details.component";
import { HttpClientModule } from "@angular/common/http";

@NgModule({
  imports: [
    NgbModule,
    FormsModule,
    HttpClientModule,
    AssignmentsRoutingModule,
    CommonModule,
    ChartsModule,
    ReactiveFormsModule,
    BsDropdownModule.forRoot(),
    TabsModule.forRoot(),
    PaginationModule.forRoot(),
    AlertModule.forRoot(),
  ],
  declarations: [AssignmentsComponent, AssignmentDetailsComponent],
  providers: [],
})
export class AssignmentsModule {}
