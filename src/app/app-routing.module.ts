import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {HomeComponent} from './pages/home/home.component';
import { RegistroComponent } from './components/registro/registro.component';
import { ProductosComponent } from './pages/productos/productos.component';

const routes: Routes = [
  { path: '', redirectTo: '/', pathMatch: 'full' },
  { path: 'inicio', component: HomeComponent },
  { path: 'motivate', component: HomeComponent },
  { path: 'cursos', component: HomeComponent },
  { path: 'registro', component: RegistroComponent },
  { path: 'productos', component: ProductosComponent },
  { path: '**', component: HomeComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes,  {useHash: false})],
  exports: [RouterModule]
})
export class AppRoutingModule { }

