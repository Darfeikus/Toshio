import { NgModule } from "@angular/core";
import { AlertModule } from "ngx-bootstrap/alert";
import { BsDropdownModule } from "ngx-bootstrap/dropdown";
import { TabsModule } from "ngx-bootstrap/tabs";
import { CommonModule } from "@angular/common";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { FormsModule } from "@angular/forms";
import { LoginRoutingModule } from "./login-routing/login-routing.module";
import { LoginComponent } from "./login.component";

@NgModule({
  imports: [
    NgbModule,
    FormsModule,
    LoginRoutingModule,
    CommonModule,
    BsDropdownModule.forRoot(),
    TabsModule.forRoot(),
    AlertModule.forRoot(),
  ],
  declarations: [LoginComponent],
  providers: [],
})
export class LoginModule {}
