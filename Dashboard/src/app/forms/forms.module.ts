
import { NgModule } from '@angular/core';
import { FormsRoutingModule } from './forms-routing/forms-routing.module';
import { FormsComponent } from './forms.component';
import { ContestRegisterComponent } from './contest-register/contest-register.component';
@NgModule({
  imports: [
    FormsRoutingModule
  ],
  declarations: [ FormsComponent, ContestRegisterComponent ],
  providers: []
})
export class FormsModule { }
