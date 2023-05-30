import { Curso } from './curso';
export class CartItemModel {

    productId: number;
    productName: string;
    description:string;
    productPrice:number;
    quantity:number;
    category:string;

    constructor(product: Curso){
      this.productId= product.id;
      this.productName = product.name;
      this.description = product.description;
      this.category = 'DIGITAL_GOODS';
      this.productPrice = product.price;
      this.quantity = 1;
    }

}

