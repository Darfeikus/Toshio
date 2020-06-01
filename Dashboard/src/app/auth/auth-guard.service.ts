import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, Router } from '@angular/router';
import decode from 'jwt-decode';
import { JwtHelperService } from '@auth0/angular-jwt';

@Injectable({
  providedIn: 'root'
})
export class AuthGuardService implements CanActivate {

  private jwtHelper: JwtHelperService;
  
  canActivate(route: ActivatedRouteSnapshot): boolean { 
      const expectedRole = route.data.expectedRole;    
      const token = localStorage.getItem('token');
      try{
        const tokenPayload = decode(token);   
        if ( 
          this.jwtHelper.isTokenExpired(token) ||
          tokenPayload.user.role !== expectedRole
        ) {
          if(tokenPayload.user.role == "student"){
            this.router.navigateByUrl("/student");
            return false;
          }
          localStorage.removeItem('token');
          localStorage.removeItem('id');
          this.router.navigateByUrl("/login");
          return false;
        }
      }catch(e){
        localStorage.removeItem('token');
        localStorage.removeItem('id');
        this.router.navigateByUrl("/login");
        return false;
      }
      return true;
  
  }
  constructor(
    private router:Router,
  ) { 
    this.jwtHelper = new JwtHelperService()
  }
}
