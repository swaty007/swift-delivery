import $ from "jquery";
import WOW from "wowjs";

class Popups {
  constructor() {
    this.menuBurger = $(".menu__burger");
    this.events();
  }

  events() {
    $(document).ready(() => {
      // popup call link
      // TO DO ONE FUNCTION FOR MENU AND MENU CALL
      $(".call-popup--show").on("click", e => {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, 1000);

        if ($(this).hasClass("main-car__call")) {
          $("#call-popup").addClass("call-popup--active");
        } else {
          $("#call-popup").toggleClass("call-popup--active");
        }
      });

      //send call popup
      $(".call-popup__brand-btn, .main-popup__brand-btn").on("click", e => {
        const textHTML = {
          success: {
            popup_title: "СПАСИБО! ВАША ЗАЯВКА ОТПРАВЛЕНА В ОБРАБОТКУ",
            popup_second_title: "Менеджер свяжется с Вами в ближайшее время",
          },
          error: {
            popup_title: "ИЗВИНИТЕ! ВАША ЗАЯВКА НЕ ОТПРАВЛЕНА",
            popup_second_title:
              "Свяжитесь с менеджером по телефону или попытайтесь отправить заявку позже",
          },
        };

        $(".main-popup .default-input, .call-popup .default-input").removeClass("default-input--error");

        const popupName =
          e.currentTarget.classList[1] == "main-popup__brand-btn"
            ? $(".main-popup #popupName")
            : $(".call-popup #popupName");
        const popupPhone =
          e.currentTarget.classList[1] == "main-popup__brand-btn"
            ? $(".main-popup #popupPhone")
            : $(".call-popup #popupPhone");

        if (popupName.val().trim() < 2 ) {
          popupName.parent().addClass("default-input--error");
          return;
        }

        if (popupPhone.val().length !== 19) {
          popupPhone.parent().addClass("default-input--error");
          return;
        }

        $.ajax({
          type: "POST",
          url: caruaData.ajaxurl,
          data: {
            action: "send_message",
            popupName: popupName.val(),
            popupPhone: popupPhone.val(),
            pageLocation: location.href
          },
          success: function(satus) {
            e.currentTarget.classList[1] == "main-popup__brand-btn"
              ? $(".main-popup").addClass("main-popup--hide")
              : $(".call-popup").removeClass("call-popup--active");
            const popup_title = $(".thank-popup__title.second-title");
            const popup_second_title = $(".thank-popup__info.main-text");
            if (satus) {
              popup_title.text(textHTML.success.popup_title);
              popup_second_title.text(textHTML.success.popup_second_title);
            } else {
              popup_title.text(textHTML.success.popup_title);
              popup_second_title.text(textHTML.success.popup_second_title);
            }
            $(".thank-popup").addClass("thank-popup--open");
            popupName.val('');
            popupPhone.val('')
          },
        });
      });

      //send subscribe
      $(".subscribe .brand-btn").on("click", e => {
        e.preventDefault();

        const textHTML = {
          success: {
            popup_title: "СПАСИБО! ВЫ ПОДПИСАЛИСЬ НА НАШИ ОБНОВЛЕНИЯ",
            popup_second_title:
              "Получайте самые выгодные предложения на вашу почту",
          },
          error: {
            popup_title: "ИЗВИНИТЕ! ВАША ПОДПИСКА НЕ АКТИВИРОВАНА",
            popup_second_title:
              "Свяжитесь с менеджером по телефону или попытайтесь отправить заявку позже",
          },
        };
        const subMail = $(".subscribe #subscribe-input").val().trim();
        const mailElement = $(".subscribe .input-btn");
        let regExEmail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        mailElement.removeClass("input-btn--error");

        if (!regExEmail.test(subMail)){
          mailElement.addClass("input-btn--error");
          return;
        }

        $.ajax({
          type: "POST",
          url: caruaData.ajaxurl,
          data: {
            action: "send_subscribe",
            subMail,
            security: caruaData.nonce,
          },
          success: function(satus) {
            // e.currentTarget.classList[1] == "main-popup__brand-btn"
            //   ? $(".main-popup").addClass("main-popup--hide")
            //   : $(".call-popup").removeClass("call-popup--active");
            const popup_title = $(".thank-popup__title.second-title");
            const popup_second_title = $(".thank-popup__info.main-text");
            if (satus) {
              popup_title.text(textHTML.success.popup_title);
              popup_second_title.text(textHTML.success.popup_second_title);
            } else {
              popup_title.text(textHTML.error.popup_title);
              popup_second_title.text(textHTML.error.popup_second_title);
            }
            $(".thank-popup").addClass("thank-popup--open");
            $(".subscribe #subscribe-input").val('');
          },
        });
      });

      //send help catch car
      $(".help__form .help__brand-btn").on("click", e => {
        e.preventDefault();
        const textHTML = {
          success: {
            popup_title: "СПАСИБО! ВАША ЗАЯВКА ОТПРАВЛЕНА В ОБРАБОТКУ",
            popup_second_title: "Менеджер свяжется с Вами в ближайшее время",
          },
          error: {
            popup_title: "ИЗВИНИТЕ! ВАША ЗАЯВКА НЕ ОТПРАВЛЕНА",
            popup_second_title:
              "Свяжитесь с менеджером по телефону или попытайтесь отправить заявку позже",
          },
        };

        $(".help__form .default-input").removeClass("default-input--error");

        const helpName = $(".help__form #yourName");
        const helpMail = $(".help__form #yourEmail");
        const helpPhone = $(".help__form #yourPhone");
        let regExEmail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;



        if (helpName.val().trim() < 2 ) {
          helpName.parent().addClass("default-input--error");
          if ($(window).width() <= 640 && !$(".help__form").hasClass("help__form--active")) {
            $(".help__form").addClass("help__form--active");
            helpName.parent().removeClass("default-input--error");
            helpName.focus();
          }
          return;
        }

        if (!regExEmail.test(helpMail.val().trim()) && helpPhone.val().length !== 19 ){
          helpMail.parent().addClass("default-input--error");
          helpPhone.parent().addClass("default-input--error");
          return;
        }


        $.ajax({
          type: "POST",
          url: caruaData.ajaxurl,
          data: {
            action: "catch_car",
            helpName: helpName.val(),
            helpMail: helpMail.val(),
            helpPhone: helpPhone.val(),
            pageLocation: location.href
          },
          success: function(satus) {
            const popup_title = $(".thank-popup__title.second-title");
            const popup_second_title = $(".thank-popup__info.main-text");
            if (satus) {
              popup_title.text(textHTML.success.popup_title);
              popup_second_title.text(textHTML.success.popup_second_title);
            } else {
              popup_title.text(textHTML.error.popup_title);
              popup_second_title.text(textHTML.error.popup_second_title);
            }
            $(".thank-popup").addClass("thank-popup--open");
            helpName.val('');
            helpMail.val('');
            helpPhone.val('');
          },
        });
      });

      //send trade in
      $(".trade-in .trade-form__brand-btn").on("click", e => {
        e.preventDefault();
        const textHTML = {
          success: {
            popup_title: "СПАСИБО! ВАША ЗАЯВКА ОТПРАВЛЕНА В ОБРАБОТКУ",
            popup_second_title: "Менеджер свяжется с Вами в ближайшее время",
          },
          error: {
            popup_title: "ИЗВИНИТЕ! ВАША ЗАЯВКА НЕ ОТПРАВЛЕНА",
            popup_second_title:
              "Свяжитесь с менеджером по телефону или попытайтесь отправить заявку позже",
          },
        };

        $(".trade-in .default-input").removeClass("default-input--error");

        const tradeBrand = $(".trade-in #markaTrade");
        const tradeModel = $(".trade-in #modelTrade");
        const tradeMileage = $(".trade-in #mileage");
        const tradeYear = $(".trade-in #yearOfRelease");
        const tradeName = $(".trade-in #yourNameTrade");
        const tradePhone = $(".trade-in #yourPhoneTrade");
        const tradeComment = $(".trade-in #comment");

        if (tradeBrand.val().trim() < 2 ) {
          tradeBrand.parent().addClass("default-input--error");
          return;
        }
        if (tradeModel.val().trim() < 2 ) {
          tradeModel.parent().addClass("default-input--error");
          return;
        }
        if (tradeMileage.val().trim() < 2 ) {
          tradeMileage.parent().addClass("default-input--error");
          return;
        }
        if (tradeYear.val().trim() < 4 ) {
          tradeYear.parent().addClass("default-input--error");
          return;
        }
        if (tradeName.val().trim() < 2 ) {
          tradeName.parent().addClass("default-input--error");
          return;
        }
        if (tradePhone.val().length !== 19) {
          tradePhone.parent().addClass("default-input--error");
          return;
        }

        $.ajax({
          type: "POST",
          url: caruaData.ajaxurl,
          data: {
            action: "trade_in",
            tradeBrand: tradeBrand.val(),
            tradeModel: tradeModel.val(),
            tradeMileage: tradeMileage.val(),
            tradeYear: tradeYear.val(),
            tradeName: tradeName.val(),
            tradePhone: tradePhone.val(),
            tradeComment: tradeComment.val()
          },
          success: function(satus) {
            const popup_title = $(".thank-popup__title.second-title");
            const popup_second_title = $(".thank-popup__info.main-text");
            if (satus) {
              popup_title.text(textHTML.success.popup_title);
              popup_second_title.text(textHTML.success.popup_second_title);
            } else {
              popup_title.text(textHTML.error.popup_title);
              popup_second_title.text(textHTML.error.popup_second_title);
            }
            $(".thank-popup").addClass("thank-popup--open");
            tradeBrand.val('');
            tradeModel.val('');
            tradeMileage.val('');
            tradeYear.val('');
            tradeName.val('');
            tradePhone.val('');
            tradeComment.val('');
          },
        });
      });

      //send buy tire
      $(".trade-form--buy-tire .trade-form__brand-btn").on("click", e => {
        e.preventDefault();
        const textHTML = {
          success: {
            popup_title: "СПАСИБО! ВАША ЗАЯВКА ОТПРАВЛЕНА В ОБРАБОТКУ",
            popup_second_title: "Менеджер свяжется с Вами в ближайшее время",
          },
          error: {
            popup_title: "ИЗВИНИТЕ! ВАША ЗАЯВКА НЕ ОТПРАВЛЕНА",
            popup_second_title:
              "Свяжитесь с менеджером по телефону или попытайтесь отправить заявку позже",
          },
        };

        $(".trade-form--buy-tire .default-input").removeClass("default-input--error");

        const tireSezon = $(".trade-form--buy-tire #sezon");
        const tireWidth = $(".trade-form--buy-tire #width");
        const tireProfile = $(".trade-form--buy-tire #profile");
        const tireDiametr = $(".trade-form--buy-tire #diametr");
        const tireBrand = $(".trade-form--buy-tire #brand");
        const tireName = $(".trade-form--buy-tire #yourNameTire");
        const tirePhone = $(".trade-form--buy-tire #yourPhoneTire");

        if (tireName.val().trim() < 2 ) {
          tireName.parent().addClass("default-input--error");
          return;
        }
        if (tirePhone.val().length !== 19) {
          tirePhone.parent().addClass("default-input--error");
          return;
        }

        $.ajax({
          type: "POST",
          url: caruaData.ajaxurl,
          data: {
            action: "buy_tire",
            tireSezon: tireSezon.text(),
            tireWidth: tireWidth.text(),
            tireProfile: tireProfile.text(),
            tireDiametr: tireDiametr.text(),
            tireBrand: tireBrand.text(),
            tireName: tireName.val(),
            tirePhone: tirePhone.val()
          },
          success: function(satus) {
            const popup_title = $(".thank-popup__title.second-title");
            const popup_second_title = $(".thank-popup__info.main-text");
            if (satus) {
              popup_title.text(textHTML.success.popup_title);
              popup_second_title.text(textHTML.success.popup_second_title);
            } else {
              popup_title.text(textHTML.error.popup_title);
              popup_second_title.text(textHTML.error.popup_second_title);
            }
            $(".thank-popup").addClass("thank-popup--open");
            tireSezon.text(tireSezon.attr('data-default'));
            tireWidth.text(tireWidth.attr('data-default'));
            tireProfile.text(tireProfile.attr('data-default'));
            tireDiametr.text(tireDiametr.attr('data-default'));
            tireBrand.text(tireBrand.attr('data-default'));
            tireName.val('');
            tirePhone.val('');
            $('.trade-form__data .default-select__current').removeClass('default-select__current--active');
          },
        });
      });

      //thank popup close
      $(".thank-popup__close, .thank-popup__brand-btn").on("click", () => {
        $(".thank-popup").removeClass("thank-popup--open");
        $('html').removeClass('popup-open');
      });

      // main popup close
      $("#mainPopupClose").on("click", function() {
        $(this)
          .parent()
          .addClass("main-popup--hide");
        $('html').removeClass('popup-open');
      });

      // call popup close
      $("#callPopupClose").on("click", function() {
        $(this)
            .parent()
            .removeClass("call-popup--active");
      });

      // open main popup
      $(".show-popup").on("click", function() {
        $(".main-popup").toggleClass("main-popup--hide");
        $('html').addClass('popup-open');
      });

      // hide help section
      $(".help__close").on("click", function() {
        $(".help").hide();
        $('html').removeClass('popup-open');
      });
    });
  }
  // Functions
}

export default Popups;
