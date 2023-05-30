import { Component, NgZone, OnInit } from '@angular/core';
import { FormBuilder, Validators, FormGroup, FormControl } from '@angular/forms';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';
import { EventoService } from 'src/app/services/evento.service';
import { UserEvento } from 'src/app/models/userevento';

@Component({
  selector: 'app-registro',
  templateUrl: './registro.component.html',
  styleUrls: ['./registro.component.css']
})
export class RegistroComponent implements OnInit{
  email = new FormControl();
  password = new FormControl();
  remember = new FormControl();
  loginForm: FormGroup;
  submitted = false;
  error = null;
  errors:any = null;
  user: UserEvento;

  // Registro
  public formSumitted = false;
  public registerForm = this.fb.group({
    id: [''],
    firstName: ['', Validators.required],
    lastName: ['', Validators.required],
    estado: ['', Validators.required],
    pais: ['', Validators.required],
    telefono: ['', Validators.required],
    movil: ['', Validators.required],
    email: [ '', [Validators.required] ],
    dondeSeEntero: ['', Validators.required]

  });
  // Registro

  constructor(
    private router: Router,
    private fb: FormBuilder,
    private eventoService: EventoService,
  ) {

  }
  username: FormControl<any>;

  ngOnInit(){

  }

  crearUsuario(){
    this.formSumitted = true;
    if(this.registerForm.invalid){
      return;
    }
    // console.log(this.registerForm.value);

    this.eventoService.crearUsuario(this.registerForm.value).subscribe(
      resp =>{
        this.router.navigateByUrl('/productos');
        Swal.fire('Registrado!', `Ya puedes comprar`, 'success');
      },(error) => {
        Swal.fire('Error', error.error.msg, 'error');
        this.errors = error.error;
      }
    );

  }


}
