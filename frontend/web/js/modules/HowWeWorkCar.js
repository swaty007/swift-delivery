import $ from "jquery";

class HowWeWorkCar {
  constructor() {
    this.menuBurger = $(".menu__burger");
    this.gogogo = 23;
    this.areaHeight = $(".hww__content").innerHeight();
    this.allCarStops = $(".hww__content .hww__item");
    this.allCarStopsHeight = this.allCarStops.innerHeight();
    this.movingCar = $(".hww__car--ride");
    this.movingCarHeight = this.movingCar.height();
    if (this.movingCar.length !== 0) {
      this.movingCarTop = this.movingCar.offset().top;
    } else {
      this.movingCarTop = 0;
    }
    this.targetElem = $(".hww")[0];
    this.targetScroll = false;
    this.block = false;
    this.stepTime = 300;
    this.h = $(window).outerHeight();
    this.y = $(window).scrollTop();
    this.events();
  }

  events() {
    // $(window).on("load", () => {
    //   if (this.targetElem) this.carToGo();
    // });
    if (this.targetElem) {
      $(window).resize(e => {
        this.areaHeight = $(".hww__content").innerHeight();
        this.h = $(window).outerHeight();
        this.movingCarTop = this.movingCar.offset().top;
      });

      $(window).on("scroll", e => {
        this.y = $(window).scrollTop();
        this.movingCarTop = this.movingCar.offset().top;

        if (
            this.y+(this.h/2) >= $(".hww > .container").position().top &&
            this.y < $(".hww__content").outerHeight(true)+$(".hww > .container").position().top &&
            !this.targetScroll
        ) {
          this.targetScroll = true;
          // TO DO stop on minus margin top
          this.carToGo();
        }
      });
    }
  }
  reload() {
    this.gogogo = 23;
    this.areaHeight = $(".hww__content").innerHeight();
    this.allCarStops = $(".hww__content .hww__item");
    this.allCarStopsHeight = this.allCarStops.innerHeight();
    this.movingCar = $(".hww__car--ride");
    this.movingCarHeight = this.movingCar.height();
    this.movingCarTop = this.movingCar.offset().top;
    this.targetElem = $(".hww")[0];
    this.h = $(window).outerHeight();
    this.y = $(window).scrollTop();
  }
  // Functions
  carToGo() {
    if ("onwheel" in document) {
      // IE9+, FF17+, Ch31+
      this.targetElem.addEventListener("wheel", this.onWheel.bind(this));
      console.log("wheel");
    } else if ("onmousewheel" in document) {
      // устаревший вариант события
      this.targetElem.addEventListener("mousewheel", this.onWheel.bind(this));
      console.log("mousewheel");
    } else {
      // Firefox < 17
      this.targetElem.addEventListener(
        "MozMousePixelScroll",
        this.onWheel.bind(this)
      );
      console.log("MozMousePixelScroll");
    }
    $("body").css("overflow", "hidden");
  }
  onWheel(e) {
    e.preventDefault ? e.preventDefault() : (e.returnValue = false);
    if (this.block) {return;}
    this.block = true;
    e = e || window.event;

    var displayCenter = this.y + (this.movingCarTop - (this.y + this.h/2));

    var last_item = $(".hww__content .hww__item--active:last");
    last_item.removeClass("hww__item--active");

    var scrollToTop = true;
    if (e.deltaY > 0 || e.wheelDelta < 0) {
      last_item = last_item.next('.hww__item');
      scrollToTop = false;
    } else {
      last_item = last_item.prev('.hww__item');
    }

    if (last_item.length === 0) {
          $("body").css("overflow", "scroll");
          const old_element = document.getElementById("hww");
          if (scrollToTop) {
            $('#hww .hww__item:first').addClass('hww__item--active');
          } else {
            $('#hww .hww__item:last').addClass('hww__item--active');
          }
          const new_element = old_element.cloneNode(true);
          old_element.parentNode.replaceChild(new_element, old_element);
          this.block = false;
          this.reload();
          setTimeout(() => {
            this.targetScroll = false;
          },2000);
          return;
    }

    // var exit_pos = $(".hww__content").position().top;
    // var exit_h = $(".hww__content").height();
    // var exit_m = $(".hww__content").outerHeight(true) - exit_h;
    // if (last_item.length === 0) {
    //   console.log('exit')
    //   if ((exit_pos+exit_m > this.y + exit_h/2) ||
    //       ($(".hww__content").outerHeight(true)+exit_pos<this.y + exit_h/2)) {
    //     console.log('true exit')
    //     $("body").css("overflow", "scroll");
    //     const old_element = document.getElementById("hww");
    //     const new_element = old_element.cloneNode(true);
    //     old_element.parentNode.replaceChild(new_element, old_element);
    //     this.block = false;
    //     setTimeout(() => {
    //       this.targetScroll = false;
    //     },3000);
    //     return;
    //   }
    //   $("body").css("overflow", "hidden");
    //   // return;
    // }
    last_item.addClass("hww__item--active");
    this.gogogo = last_item.position().top + 40;

    var diff = this.gogogo - this.movingCar.position().top;

    $("html, body").animate({ scrollTop: displayCenter + diff }, this.stepTime, "linear");
    this.movingCar.animate({ top: this.gogogo }, this.stepTime, "linear", () => {
      this.movingCarTop = this.movingCar.offset().top;
      this.y = $(window).scrollTop();
      this.block = false;
    });
  }
}

export default HowWeWorkCar;
