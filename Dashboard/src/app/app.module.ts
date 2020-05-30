import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { RouterModule } from "@angular/router";
import { AppComponent } from "./app.component";
import { BlankTemplateComponent } from "./template/blank-template.component";
import { LeftNavTemplateComponent } from "./template/left-nav-template.component";
import { AppRoutingModule, routes } from "./app.routing";
import { PageNotFoundComponent } from "./page-not-found/page-not-found.component";
import { HeaderComponent } from "./shared/header/header.component";
import { NavigationComponent } from "./shared/navigation/navigation.component";
import { ContestsDashboardComponent } from "./contests/contests-dashboard/contests-dashboard.component";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { CommonModule } from "@angular/common";
import { FormsModule } from '@angular/forms';
import { LoginModule } from './login/login.module';
import { HttpClientModule } from '@angular/common/http';

@NgModule({
  declarations: [
    AppComponent,
    BlankTemplateComponent,
    PageNotFoundComponent,
    HeaderComponent,
    LeftNavTemplateComponent,
    NavigationComponent,
    ContestsDashboardComponent,
  ],
  imports: [
    CommonModule,
    BrowserModule,
    AppRoutingModule,
    RouterModule.forRoot(routes),
    NgbModule,
    FormsModule,
    LoginModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
