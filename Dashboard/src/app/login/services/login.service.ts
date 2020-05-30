import { Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { RequestsService } from 'app/shared/services/requests.service';

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(
    private requestsService: RequestsService
  ) { }

  makeLogin(data: FormGroup){
    let body = new FormData();
    body.append('student_id', data.get('studentId').value);
    body.append('password', data.get('password').value);
    return this.requestsService.post("login", body);
  }
}
