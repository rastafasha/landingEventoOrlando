import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FooterComponent } from './footer/footer.component';
import { HeaderComponent } from './header/header.component';
import { IntroComponent } from './intro/intro.component';
import { MotivateComponent } from './motivate/motivate.component';
import { InfoComponent } from './info/info.component';
import { CursosComponent } from './cursos/cursos.component';
import { RegistroComponent } from './registro/registro.component';
import { AppRoutingModule } from '../app-routing.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CartComponent } from './cart/cart.component';
import { CartItemComponent } from './cart-item/cart-item.component';
import { ProductItemComponent } from './product-item/product-item.component';
//pago
import { NgxPayPalModule } from 'ngx-paypal';
import { ModalComponent } from './modal/modal.component';

@NgModule({
  declarations: [
    HeaderComponent,
    FooterComponent,
    IntroComponent,
    MotivateComponent,
    InfoComponent,
    CursosComponent,
    RegistroComponent,
    CartComponent,
    CartItemComponent,
    ProductItemComponent,
    ModalComponent
  ],
  exports: [
    HeaderComponent,
    FooterComponent,
    IntroComponent,
    MotivateComponent,
    InfoComponent,
    CursosComponent,
    RegistroComponent,
    CartComponent,
    CartItemComponent,
    ProductItemComponent,
    ModalComponent

  ],
  imports: [
    CommonModule,
    AppRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    NgxPayPalModule,
  ]
})
export class ComponentsModule { }
