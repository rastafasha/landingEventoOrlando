import { Injectable } from '@angular/core';
import { Observable, Subject } from 'rxjs';
import { Curso } from '../models/curso';

@Injectable({
  providedIn: 'root'
})
export class MessageService {

  message = new Subject()

  constructor() { }

  sendMessage(product: Curso):void{
    this.message.next(product);
  }

  getMessage(): Observable<any>{
    return this.message.asObservable();
  }
}
