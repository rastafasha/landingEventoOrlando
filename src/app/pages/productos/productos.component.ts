import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Curso } from 'src/app/models/curso';
import { UserEvento } from 'src/app/models/userevento';
import { CursosService } from 'src/app/services/cursos.service';
import { EventoService } from 'src/app/services/evento.service';
import { MessageService } from 'src/app/services/message.service';

@Component({
  selector: 'app-productos',
  templateUrl: './productos.component.html',
  styleUrls: ['./productos.component.css']
})
export class ProductosComponent implements OnInit {

  products: Curso[] = []
  user: UserEvento;
  error: string;
  id:any;
  userprofile!: any;

  constructor(
    private cursoService: CursosService,
    private eventoService: EventoService,
    private messageService: MessageService,
    private activatedRoute: ActivatedRoute,
  ) {}

  ngOnInit(): void {
    this.loadProducts();
    // this.loadCursos();
    this.getUser();
    window.scrollTo(0,0);
  }

  loadProducts(): void{
    this.products = this.cursoService.getProducts();

  }


  getUser(): void {

    this.user = JSON.parse(localStorage.getItem('user'));
      this.id = this.user.id;
    // this.activatedRoute.params.subscribe( ({id}) => this.getUserProfile(id));
  }

  getUserProfile(id:any){
    id  = this.user.id
    this.eventoService.getUsuario(id).subscribe(
      res =>{
        this.userprofile = res;
        error => this.error = error
      }
      );
  }
}
