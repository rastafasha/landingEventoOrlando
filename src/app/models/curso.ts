import { environment } from "src/environments/environment";

const base_url = environment.apiUrlMedia;
export class Curso {
  id: number;
  name: string;
  category?: 'DIGITAL_GOODS';
  description: string;
  price: any;
  image: string;
  videoUrl: string;
  modal: string;
  created_at: string;
  updated_at: string;
  status?: 'APPROVED' | 'PENDING' | 'REJECTED';
  // description: string;
  // category:string;



  constructor(id, name, description, category, price,  image,   ){
    this.id = id;
    this.name = name;
    this.description = description;
    this.image = image;
    this.category = category;
    this.price = price;
  }


  get imagenUrl(){

    if(!this.image){
      return `${base_url}/cursos/no-image.jpg`;
    } else if(this.image.includes('https')){
      return this.image;
    } else if(this.image){
      return `${base_url}/cursos/${this.image}`;
    }else {
      return `${base_url}/no-image.jpg`;
    }

  }
}

// const PUBLISHED = 'PUBLISHED';
//     const PENDING = 'PENDING';
//     const REJECTED = 'REJECTED';
