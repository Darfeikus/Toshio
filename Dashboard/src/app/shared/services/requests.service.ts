import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams, HttpResponse } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class RequestsService {

  private baseURL:string = "http://localhost:8000/api/"

  constructor(
    private http: HttpClient
  ) { }

  post(url: string, body: FormData, options?)
  {
    if(!options) 
      options = {}
    
    if(localStorage.getItem('token'))
      options.headers = new HttpHeaders({'Authentication': localStorage.getItem('token')});
   
    return this.http.post<any>(this.baseURL+url, body, options)
  }

  get(url: string, options?)
  {
    if(!options) 
      options = {}
    
    if(localStorage.getItem('token'))
      options.headers = new HttpHeaders({'Authentication': localStorage.getItem('token')});
   
    return this.http.get<any>(this.baseURL+url, options)
  }
}
