import { Injectable } from '@angular/core';
import { Curso } from '../models/curso';
import { environment } from 'src/environments/environment';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs';
const baseUrl = environment.apiUrl;

@Injectable({
  providedIn: 'root'
})
export class CursosService {
  cursos: Curso;

  products: Curso [] = [
    new Curso(1, 'Curso Orlando Express de Orfebrería', 'Curso Orlando Express de Orfebrería', 'DIGITAL_GOODS', 420.00, './assets/images/cursos/basicoOrfebreria.png'),
    new Curso(2, 'Curso Orlando Avanzado de Orfebrería', 'Curso Orlando Avanzado de Orfebrería', 'DIGITAL_GOODS',  420.00, './assets/images/cursos/1.jpg'),
    new Curso(3, 'Curso Orlando Metal Clay', 'Curso Orlando Metal Clay', 'DIGITAL_GOODS',  350.00, './assets/images/cursos/metalClay.png'),
    new Curso(4, 'Curso Orlando Esmalte al Fuego', 'Curso Orlando Esmalte al Fuego', 'DIGITAL_GOODS', 220.00, './assets/images/cursos/esmalteAlFuego.png'),
    new Curso(5, 'Curso Orlando Alambrismo', 'Curso Orlando Alambrismo', 'DIGITAL_GOODS', 200.00, './assets/images/cursos/alambrismobasico.png'),
    new Curso(6, 'Curso Orlando Resina y Madera', 'Curso Orlando Resina y Madera', 'DIGITAL_GOODS',  240.00, './assets/images/cursos/04.jpg'),
    new Curso(7, 'Curso Orlando Resina y Metal', 'Curso Orlando Resina y Metal', 'DIGITAL_GOODS',  350.00, './assets/images/cursos/03.jpg'),
    new Curso(8, 'Curso Orlando Reconstituido', 'Curso Orlando Reconstituido', 'DIGITAL_GOODS',  380.00, './assets/images/cursos/reconstituido.png'),
    new Curso(9, 'Curso Orlando Modelado en Cera', 'Curso Orlando Modelado en Cera', 'DIGITAL_GOODS',  220.00, './assets/images/cursos/modeladocera.png'),
    new Curso(10, 'Curso Orlando Remaches', 'Curso Orlando Remaches', 'DIGITAL_GOODS',  220.00, './assets/images/cursos/remaches.png'),
    new Curso(11, 'Curso Orlando Anillo 6 Uñas', 'Curso Orlando Anillo 6 Uñas', 'DIGITAL_GOODS',  380.00, './assets/images/cursos/engasteDeUnas.png'),
    new Curso(12, 'Curso Orlando Anillo de Volumen', 'Curso Orlando Anillo de Volumen', 'DIGITAL_GOODS',  380.00, './assets/images/cursos/anillovolumen.png'),
    new Curso(13, 'Curso Orlando Anillo Antiestress', 'Curso Orlando Anillo Antiestress', 'DIGITAL_GOODS',  220.00, './assets/images/cursos/anilloantiestress.png'),
    new Curso(14, 'Curso Orlando Cadena China', 'Curso Orlando Cadena China', 'DIGITAL_GOODS',  420.00, './assets/images/cursos/cadenachina.png'),
  ]

  constructor(
    private http: HttpClient
  ) { }


  getProducts(): Curso[]{
    return this.products;
  }


}
