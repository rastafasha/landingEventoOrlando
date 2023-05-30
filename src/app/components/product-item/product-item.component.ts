import { Component, Input, OnInit } from '@angular/core';
import { Curso } from 'src/app/models/curso';
import { MessageService } from '../../services/message.service';

@Component({
  selector: 'app-product-item',
  templateUrl: './product-item.component.html',
  styleUrls: ['./product-item.component.css']
})
export class ProductItemComponent implements OnInit {

  @Input() product: Curso;


  constructor(
    private messageService: MessageService
    ) { }

  ngOnInit(): void {
  }

  addToCart(): void{
    console.log('sending...')
    this.messageService.sendMessage(this.product);
  }

}
