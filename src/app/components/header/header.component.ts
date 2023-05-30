import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";
import { ViewportScroller } from "@angular/common";

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit{
  constructor(private scroller: ViewportScroller, private router: Router) {}
  ngOnInit() {
    this.router.navigate(["/"]);
  }


  goToInicio() {
    this.scroller.scrollToAnchor("inicio");
    // this.router.navigate([], { fragment: "inicio" });
  }
  goToMotivate() {
    this.scroller.scrollToAnchor("motivate");
    // this.router.navigate([], { fragment: "motivate" });
  }
  goToCursos() {
    this.scroller.scrollToAnchor("cursos");
    // this.router.navigate([], { fragment: "cursos" });
  }
  goToRegistro() {
    this.scroller.scrollToAnchor("registro");
    // this.router.navigate([], { fragment: "registro" });
  }

  // goDown2() {
  //   //this.scroller.scrollToAnchor("targetGreen");
  //   var targetGreen = document.getElementById("targetGreen").scrollIntoView({
  //     behavior: "smooth",
  //     block: "start",
  //     inline: "nearest"
  //   });
  // }

  goDown3() {
    this.router.navigate([], { fragment: "targetBlue" });
  }
}
