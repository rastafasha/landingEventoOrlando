import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { RegisterForm } from '../interfaces/register-form.interface';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import {map} from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import { UserEvento } from '../models/userevento';

@Injectable({
  providedIn: 'root'
})
export class EventoService {

  serverUrl = environment.apiUrl;
  public user;

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    this.user;
  }

  guardarLocalStorage( user:any){
    // localStorage.setItem('token', JSON.stringify(token));
  localStorage.setItem('user', JSON.stringify(user));
  }


  crearUsuario(formData: RegisterForm){
    return this.http.post(`${this.serverUrl}/eventoorlando/store`, formData)
    .pipe(map(user => {
      this.guardarLocalStorage(user);
    }));

  }

  getUsuario(id:number): Observable<any> {

    const url = `${this.serverUrl}/eventoorlando/show/${id}`;
    return this.http.get<any>(url)
      .pipe(
        map((resp:{ok: boolean, user: UserEvento}) => resp.user)
        );
  }


}
