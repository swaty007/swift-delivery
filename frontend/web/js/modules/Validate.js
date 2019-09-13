import $ from "jquery";

class Validate {
  constructor() {
    this.events();
  }

  events() {
    $(document).ready(() => {
      this.addMaskPhone('.help__form #yourPhone, .main-popup #popupPhone, ' +
        '.call-popup #popupPhone, .trade-form #yourPhoneTrade, .trade-form #yourPhoneTire');

      this.numberFilter('#calculate__cost, #calculate__eng-volume');

      $('.help__form').on('submit', (e) => {
        e.preventDefault();
        $(".help__form .help__input").removeClass('default-input--error');

        let email = $('.help__form #yourEmail');
        let phone = $('.help__form #yourPhone');
        let name = $('.help__form #yourName');

        if (name.val().trim().length > 1) {
          if (!this.emailValidate(email.val())) {
            if (phone.val().length === 19) {
              $(e.target)[0].submit();
            } else {
              email.parent().addClass('default-input--error');
              phone.parent().addClass('default-input--error');
            }
          } else {
            $(e.target)[0].submit();
          }
        } else {
          name.parent().addClass('default-input--error');
        }
      });
    });
  }

  emailValidate(data){
    let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    return reg.test(data)
  }

  numberFilter(elements) {
    [].forEach.call( document.querySelectorAll(elements), function(input) {
      ["input", "keydown", "keyup", "mousedown", "mouseup"].forEach(function(event) {
        input.addEventListener(event, function() {
          if (/^\d*\.?\d*$/.test(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
          } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
          }
        });
      });
    });
  }

  addMaskPhone(elements){
      [].forEach.call( document.querySelectorAll(elements), function(input) {
        var keyCode;
        function mask(event) {
          event.keyCode && (keyCode = event.keyCode);
          var pos = this.selectionStart;
          if (pos < 3) event.preventDefault();
          var matrix = "+38 (___) ___ __ __",
            i = 0,
            def = matrix.replace(/\D/g, ""),
            val = this.value.replace(/\D/g, ""),
            new_value = matrix.replace(/[_\d]/g, function(a) {
              return i < val.length ? val.charAt(i++) || def.charAt(i) : a
            });
          i = new_value.indexOf("_");
          if (i != -1) {
            i < 5 && (i = 3);
            new_value = new_value.slice(0, i)
          }
          var reg = matrix.substr(0, this.value.length).replace(/_+/g,
            function(a) {
              return "\\d{1," + a.length + "}"
            }).replace(/[+()]/g, "\\$&");
          reg = new RegExp("^" + reg + "$");
          if (!reg.test(this.value) || this.value.length < 5 || keyCode > 47 && keyCode < 58) this.value = new_value;
          if (event.type == "blur" && this.value.length < 5)  this.value = ""
        }

        input.addEventListener("input", mask, false);
        input.addEventListener("focus", mask, false);
        input.addEventListener("blur", mask, false);
        input.addEventListener("keydown", mask, false);
        input.addEventListener("mouseup", function (e, el) {
          var pos = this.selectionStart;
          if (pos < 4) {
            input.setSelectionRange(5, 5);
          }
        }, false);

      });

  }
}

export default Validate;
