import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment.development';
import { LoginModel } from '../models/login-model.model';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(
    private httpClient: HttpClient
  ) { }

  public login(login: LoginModel): Observable<any>
  {
    return this.httpClient.post<any>(environment.baseUrl + '/auth/login', login);
  }
}
