import $ from "jquery";
import WOW from "wowjs";
import fancybox from "fancybox";
import {initProfitableSlider} from "./Filter/slider";

if ($(".album__active, #albumItems .album__item")[0]) {
  fancybox($);
}

class Anim {
  constructor() {
    this.menuBurger = $(".menu__burger");
    this.events();
  }

  events() {
    $(window).on("load", function() {
      new WOW.WOW().init();
      $(".preloader").addClass("preloader--done");
      $(".header__social").addClass("animated fadeInLeft");
      $(".header .header__content-photo").addClass("animated fadeInRight");
      $(".menu, .header .main-car__desc").addClass("animated fadeInDown");
      $(
        ".header__main-title, .header__main-text, .header__brand-btn, .header .default-btn"
      ).addClass("animated fadeInUp");
    });

    $(document).ready(() => {
      // this.initDiagram();
      let startPos = 0;
      let sliderConteiner = $(".reviews__slider-content");
      $(".reviews__slider .reviews__photo").on("click", function() {
        let direction = "right";
        let stepSlide = -200;

        if ($(window).width() < 1200) {
          direction = "left";
          if ($(window).width() < 641) {
            stepSlide = -190;
          }
        }

        let slideIndex = $(this)
          .parent()
          .index();
        sliderConteiner.css("transition", direction + " .3s");
        sliderConteiner.css(direction, startPos + stepSlide * slideIndex);

        // reviews slider
        let currentSlide = $(this).parent();
        let prevSlide = $(".reviews__slider .reviews__item--active");
        $(".reviews__slider .reviews__item").removeClass(
          "reviews__item--active"
        );
        currentSlide.addClass("reviews__item--active");

        setTimeout(function() {
          prevSlide.appendTo(".reviews__slider-content");
          $(
            currentSlide
              .prevAll()
              .get()
              .reverse()
          ).each(function() {
            $(this).appendTo(".reviews__slider-content");
          });
          sliderConteiner.css("transition", "none");
          sliderConteiner.css(direction, 0);
        }, 300);

        // change active data
        let $name = $(this)
          .parent()
          .find(".reviews__info-name")
          .text();
        let $post = $(this)
          .parent()
          .find(".reviews__info-post")
          .text();
        let $desc = $(this)
          .parent()
          .find(".reviews__info-desc")
          .text();
        $(".reviews__active-item .reviews__info-name").text($name);
        $(".reviews__active-item .reviews__info-post").text($post);
        $(".reviews__active-item .reviews__info-desc").text($desc);
      });

      // next slide link
      $("#review-next").on("click", function() {
        $(".reviews__slider .reviews__item:eq(1) .reviews__photo").click();
      });

      $("#processTradeBtn").on("click", function(e) {
        e.preventDefault();
        let link = $(this).attr("href");
        $("html, body").animate(
          {
            scrollTop: $(link).offset().top,
          },
          1500
        );
      });

      // open selects
      $(".default-select__current:not(.double-select__current)").on(
        "click",
        function() {
          let el = $(this)
            .parent()
            .find(".default-select__items");
          $(".default-select__items")
            .not(el)
            .removeClass("default-select__items--open");
          el.toggleClass("default-select__items--open");
        }
      );
      $(document).click(function(event) {
        let $target = $(event.target),
            select = $target.closest('.default-select');
        if(!select.length) {
          $(".default-select__items--open").removeClass("default-select__items--open");
        }
      });

      //trade in btn
      $("#tradeInBtn, .process__brand-btn").on("click", function(e) {
        e.preventDefault();
        $(".trade-form .default-select__current:not(.double-select__current)")
          .eq(0)
          .click();
        $("html, body").animate({ scrollTop: 0 }, 1000);
        $('#markaTrade').focus();
      });

      //buy tires btn
      $(".brand-btn.header__brand-btn[data-click='tires'], .brands__brand-btn[data-click='tires']").on("click", function(e) {
        e.preventDefault();
        const formTradeTire = $('.trade-form.trade-form--buy-tire');
        const topEl = formTradeTire.position();
        $("html, body").animate({ scrollTop: topEl.top }, 1000);
        if (!$('#sezon').next().hasClass('default-select__items--open')) {
          $('#sezon').click();
        }
      });

      // faq spoilers
      $(".faq__item").on("click", function() {
        $(".faq__item")
            .not(this)
            .removeClass('faq__item--scroll faq__item--show');
        if ($(this).hasClass('faq__item--show')) {
          $(this)
              .removeClass('faq__item--scroll')
              .delay(300)
              .queue(function(){
                $(this).removeClass('faq__item--show').dequeue();
              });
        } else {
          $(this)
              .addClass('faq__item--show')
              .delay(300)
              .queue(function(){
                $(this).addClass('faq__item--scroll').dequeue();
              });
        }
      });

      // menu call link
      $(".menu__call-link, .menu-popup__call, .faq__brand-btn").on("click", function(e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, 1000);

        if (
          $(this).hasClass("main-car__call") ||
          $(this).hasClass("call-link--popup")
        ) {
          $("#call-popup").addClass("call-popup--active");
        } else {
          $("#call-popup").toggleClass("call-popup--active");
        }
        if ($(this).hasClass("call-link--popup")) {
          $(".menu-popup").removeClass("menu-popup--open");
          $(".menu__burger").removeClass("menu__burger--close");
        }

        $("#call-popup #popupName").focus();
      });

      // menu burger on < 1200
      this.menuBurger.on("click", function() {
        $(this).addClass("menu__burger--close");
        $("#popup-burger").addClass("menu__burger--close");
        $(".menu-popup").addClass("menu-popup--open");
      });

      // mobile sort menu on lots page
      $("#sortMenu").on("click", function() {
        $(this).toggleClass("mobile-select__title--active");
        $(this)
          .parent()
          .find(".mobile-select__sort-items")
          .toggleClass("mobile-select__sort-items--active");
      });

      // filter menu on lots page
      $("#filterMenu").on("click", function() {
        $(this).toggleClass(
          "mobile-select__title--active mobile-select__title--full"
        );
        $(this)
          .parent()
          .find(".mobile-select__filter-content")
          .toggleClass("mobile-select__filter-content--active");
      });

      // menu burger
      $("#popup-burger").on("click", () => {
        this.menuBurger.removeClass("menu__burger--close");
        $(".menu-popup").removeClass("menu-popup--open");
      });

      // filter-sidebar spoiler
      $(".filter-car__title").on("click", function() {
        $(this).toggleClass("filter-car__title--open");
        $(this)
          .parent()
          .find(".filter-car__details")
          .toggleClass("filter-car__details--open");
      });

      // More information link
      $(".more-link").on("click", function(e) {
        e.preventDefault();
        let scrollHeight = $("header")
          .next()
          .offset().top;
        $("html, body").animate({ scrollTop: scrollHeight }, 1000);
      });

      // hide result info
      $(".filter-sidebar__result-close").on("click", function() {
        $(this)
          .parent()
          .hide();
      });

      // show info on calculator
      $(".calculation__link").hover(function() {
        $(this)
          .parent()
          .next()
          .addClass("calculation__notice--show")
          .focus();
      });

      // hide info on calculator
      $(".calculation__notice").focusout(function() {
        $(this).removeClass("calculation__notice--show");
      });

      // $(document).mousemove(function (e) {
      //   const cursor = $("#cursor");
      //   cursor
      //     .css("top", (e.pageY - 20) + "px")
      //     .css("left", (e.pageX - 20) + "px")
      // });
      // lots item slider
      $(".album__active-prev, .album__active-next").on("click", function(e) {
          e.stopPropagation();
          e.preventDefault();
        let items = [];
        let activeImage = $(".album__active");

        $("#albumItems .album__item").each(function() {
          items.push($(this).attr("data-link"));
        });

        if ($(this).hasClass("album__active-next")) {
          items.push(activeImage.attr("data-link"));
          activeImage.attr("data-link", items[0]);
          activeImage.css("background-image", "url(" + items[0] + ")");
          items.splice(0, 1);
        } else {
          items.unshift(activeImage.attr("data-link"));
          activeImage.attr("data-link", items[items.length - 1]);
          activeImage.css(
            "background-image",
            "url(" + items[items.length - 1] + ")"
          );
          items.splice(items.length - 1, 1);
        }

        $("#albumItems .album__item").each(function(index, value) {
          $(this).attr("data-link", items[index]);
          // $(this).css("background-image", "url(" + items[index] + ")");
        });
      });
      if ($(".album__active, #albumItems .album__item")[0]) {
        $(".album__active, #albumItems .album__item").fancybox({
            type        : 'image',
            openEffect  : 'none',
            closeEffect : 'none'
        });
      }
      // activate card slider in search-car section
      this.cardsSlider($(window).width() < 1200);
      // activate cars slider in propose section
      this.addCarsSlider($(window).width() < 1200);

      // reload sliders on resize device
      $(window).on("resize", e => {
        this.cardsSlider($(window).width() < 1200);
        this.addCarsSlider($(window).width() < 1200);
      });
      // scroll to top btn
      $(".up-btn").on("click", function(e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, 1000);
      });
      // profitable slider
      initProfitableSlider();
    });
    // LINK FROM SEARCH SECTION
    $(".search-car__cards .car-card").on("click", function() {
      window.location.href = `/lots/?lotsCountry=%2522usa%2522&brand=${$(this).data(
          "brand-link"
      )}&model=${$(this).data("model-link")}`;
    });
    $(".search-car__filter-item").on("click", function() {
      let brand = $(this).data("brand-link");
      if (brand == "all") {
        window.location.href = `/lots/?lotsCountry=%2522usa%2522`;
      } else {
        window.location.href = `/lots/?lotsCountry=%2522usa%2522&brand=${brand}`;
      }
    });
  }
  // Functions

  // find auto slider
  cardsSlider(isActive) {
    if (isActive) {
      let startPos = -285;
      let stepSlider = 380;
      let totalSlide = $(".search-car__cards > .car-card").length;

      if ($(window).width() < 641) {
        startPos = -305;
        stepSlider = 305;
      }

      $(".search-car__cards").css("left", startPos + "px");
      if (!$(".search-car__dots").length) {
        $(".search-car__wrap").after(
          '<div class="slider-dot search-car__dots"></div>'
        );
        for (let i = 0; i < totalSlide; i++) {
          $(".search-car__dots").append('<div class="slider-dot__item"></div>');
        }
        $(".search-car__dots .slider-dot__item")
          .eq(1)
          .addClass("slider-dot__item--current");
      }
      $(".search-car__dots .slider-dot__item").on("click", function(e) {
        let slideIndex = ($(this).index() - 1) * -1;
        $(".search-car__cards").css("left", startPos + stepSlider * slideIndex);

        $(".search-car__dots .slider-dot__item").removeClass(
          "slider-dot__item--current"
        );
        $(this).addClass("slider-dot__item--current");
      });

      $(".search-car__cards").on("swipeleft swiperight", function(e) {
        let cur_index = $(".search-car__dots .slider-dot__item--current").index(),
            slideIndex;
        if (e.type === "swiperight" && cur_index > 0) {//prev
          cur_index--;
        } else if (cur_index < totalSlide - 1) {
          cur_index++;
        }
        slideIndex = (cur_index - 1) * -1;

        $(".search-car__cards").css("left", startPos + stepSlider * slideIndex);

        $(".search-car__dots .slider-dot__item").removeClass(
            "slider-dot__item--current"
        );
        $(".search-car__dots .slider-dot__item")
            .eq(cur_index)
            .addClass("slider-dot__item--current");
      });

    } else {
    }
  }

  addCarsSlider(isActive) {
    if (isActive) {
      let totalSlides = $(".propose__cars > .main-car").length;

      let slides = [
        {
          name: $(".main-car--left .main-car__desc-name").html(),
          price: $(".main-car--left .main-car__desc-price").html(),
          img: $(".main-car--left .main-car__photo").attr("src"),
        },
        {
          name: $(".propose__main-car .main-car__desc-name").html(),
          price: $(".propose__main-car .main-car__desc-price").html(),
          img: $(".propose__main-car .main-car__photo").attr("src"),
        },
        {
          name: $(".main-car--right .main-car__desc-name").html(),
          price: $(".main-car--right .main-car__desc-price").html(),
          img: $(".main-car--right .main-car__photo").attr("src"),
        },
      ];

      let currentSlideIndex = 1;
      let prevSlideIndex = 0;

      if (!$(".propose__dots").length) {
        $(".propose__cars").after(
          '<div class="slider-dot propose__dots"></div>'
        );
        for (let i = 0; i < slides.length; i++) {
          $(".propose__dots").append('<div class="slider-dot__item"></div>');
        }
        $(".propose__dots .slider-dot__item")
          .eq(currentSlideIndex)
          .addClass("slider-dot__item--current");
      }

      $(".propose__dots .slider-dot__item").on("click", function() {
        currentSlideIndex = $(this).index();
        let nextSlideIndex = 2;

        // if (newIndex < currentSlideIndex) {
        //   prevElem = 'right';
        //   nextElem = 'left';
        // }

        if (currentSlideIndex + 1 < slides.length) {
          nextSlideIndex = currentSlideIndex + 1;
        } else {
          nextSlideIndex = 0;
        }
        if (currentSlideIndex - 1 >= 0) {
          prevSlideIndex = currentSlideIndex - 1;
        } else {
          prevSlideIndex = slides.length - 1;
        }

        let prevElem = "left";
        let nextElem = "right";

        $(".propose__dots .slider-dot__item").removeClass(
          "slider-dot__item--current"
        );
        $(this).addClass("slider-dot__item--current");

        $(".main-car--" + prevElem + " .main-car__desc-name").html(
          slides[prevSlideIndex].name
        );
        $(".main-car--" + prevElem + " .main-car__desc-price").html(
          slides[prevSlideIndex].price
        );
        $(".main-car--" + prevElem + " .main-car__photo").attr(
          "src",
          slides[prevSlideIndex].img
        );

        $(".main-car--" + nextElem + " .main-car__desc-name").html(
          slides[nextSlideIndex].name
        );
        $(".main-car--" + nextElem + " .main-car__desc-price").html(
          slides[nextSlideIndex].price
        );
        $(".main-car--" + nextElem + " .main-car__photo").attr(
          "src",
          slides[nextSlideIndex].img
        );

        $(".propose__main-car .main-car__desc-name").html(
          slides[currentSlideIndex].name
        );
        $(".propose__main-car .main-car__desc-price").html(
          slides[currentSlideIndex].price
        );
        $(".propose__main-car .main-car__photo").attr(
          "src",
          slides[currentSlideIndex].img
        );
      });

      $(".main-car.propose__main-car").on("swipeleft swiperight", function(
        e,
        data
      ) {
        let prevElem = "left";
        let nextElem = "right";

        if (e.type === "swiperight") {
          prevElem = "right";
          nextElem = "left";
        }

        $(".main-car--" + nextElem + " .main-car__desc-name").html(
          slides[currentSlideIndex].name
        );
        $(".main-car--" + nextElem + " .main-car__desc-price").html(
          slides[currentSlideIndex].price
        );
        $(".main-car--" + nextElem + " .main-car__photo").attr(
          "src",
          slides[currentSlideIndex].img
        );

        if (currentSlideIndex - 1 >= 0) {
          prevSlideIndex = currentSlideIndex - 1;
        } else {
          prevSlideIndex = totalSlides - 1;
        }

        $(".main-car--" + prevElem + " .main-car__desc-name").html(
          slides[prevSlideIndex].name
        );
        $(".main-car--" + prevElem + " .main-car__desc-price").html(
          slides[prevSlideIndex].price
        );
        $(".main-car--" + prevElem + " .main-car__photo").attr(
          "src",
          slides[prevSlideIndex].img
        );

        if (currentSlideIndex + 1 < totalSlides) {
          currentSlideIndex++;
        } else {
          currentSlideIndex = 0;
        }

        $(".propose__main-car .main-car__desc-name").html(
          slides[currentSlideIndex].name
        );
        $(".propose__main-car .main-car__desc-price").html(
          slides[currentSlideIndex].price
        );
        $(".propose__main-car .main-car__photo").attr(
          "src",
          slides[currentSlideIndex].img
        );

        $(".propose__dots .slider-dot__item").removeClass(
          "slider-dot__item--current"
        );
        $(".propose__dots .slider-dot__item")
          .eq(currentSlideIndex)
          .addClass("slider-dot__item--current");
      });
    } else {
      $(".main-car.propose__main-car").off("swipeleft swiperight");
      $(".propose__dots .slider-dot__item").off("click");
    }
  }
}

export default Anim;
