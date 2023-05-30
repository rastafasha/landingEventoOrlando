import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { Payment } from 'src/app/models/payment';
import { User } from 'src/app/models/user';
import { NgbActiveModal } from '@ng-bootstrap/ng-bootstrap';
import { PaymentService } from 'src/app/services/payment.service';
import { Curso } from 'src/app/models/curso';


@Component({
  selector: 'app-modal',
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.css']
})
export class ModalComponent implements OnInit {

  @Input() reference;
  @Input() amount;
  @Input() items;
  @Input() email;
  @Input() name;
  @Input() surname;


  public PaymentRegisterForm: FormGroup;
  paymentSeleccionado:Payment;
  curso:Curso;

  pagopaypal;
  user:User;
  constructor(
    public activeModal:NgbActiveModal,
    public router: Router,
    private fb: FormBuilder,
    private paymentService: PaymentService,
  ) {
  }

  ngOnInit(): void {
    this.getUser();
    this.procesarPagoPaypal(this.reference,this.amount, this.curso);
  }


  procesarPagoPaypal(reference: any, amount: any, curso:any
    ){
    //crear

    let data = {
      referencia: reference,
      metodo: 'Paypal',
      monto: amount,
      curso: this.curso.id,
      user_id: this.user.id,
    }
    if(data){
      this.paymentService.create(data)
      .subscribe( (resp: any) =>{
        this.pagopaypal = resp;
      })
    }

  }


  getUser(): void {
    this.user = JSON.parse(localStorage.getItem('user'));
  }



}
