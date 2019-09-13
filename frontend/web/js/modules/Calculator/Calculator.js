import $ from "jquery";
import {
  constantParams,
  copardPrices,
  iaaiPrices,
  deliveryInUSA,
  deliveryFromUSA,
  deliverySendUSA
} from "./constForCalc";
import { between, hasProperty } from "./../utils";

class Calculator {
  // 1. describe and create/initiate our object
  constructor() {
    this.rate;
    this.calculatorContainer = $(".calculate");
    this.calculatorResult = $(".calculate__result");
    this.calculateBtn = $(".calculate__brand-btn");
    this.yearSelect = $(".calculate__year");
    this.rendered = false;
    this.firstStart = true;
    this.isValid = false;
    // this.isSpinnerVisible  false;
    this.form = {
      coefYear: {
        el: $(".calculate__year"),
        getValue: () => +this.form.coefYear.el.find(".default-select__item--active").data("value"),
      },
      engineVol: {
        el: $("#calculate__eng-volume").parent('div'),
        getValue: () => +Number($("#calculate__eng-volume").val()),
      },
      fuelType: {
        el: $(".calculate__engine-items"),
        getValue: () => this.form.fuelType.el.find(".calculate__engine-item--active").data("fuelType"),
      },
      carPrice: {
        el: $("#calculate__cost").parent('div'),
        getValue: () => +$("#calculate__cost").val(),
      },
      destPort: {
        el: $(".calculate__port-arive"),
        getValue: () => this.form.destPort.el.find(".default-select__item--active").data("id-from"),
      },
      inUSADeliveryName: {
        el: $(".calculate__delivery-in-usa"),
        getValue: () => this.form.inUSADeliveryName.el.find(".default-select__item--active").data("name-in"),
      },
    };
    if ($(".calculate__content")[0]) {
      this.events();
    }
  }

  // 2. events
  events() {
    if (!this.firstStart) {
      this.firstStart = false;
      return;
    }
    // ONLOAD
    $(window).one("load", () => {
      this.generateYearAndCoeficient();
      this.getCurrRate();
      this.deliveryFromHTMLGenerate();
      this.deliveryInHTMLGenerate();
      var carLot = {
        year: $('.lot__title').attr('data-year'),
        fuel: {
          title: $('.lot__info-value--fuel').attr('data-fuel'),
          id: $('.lot__info-value--fuel').attr('data-fuel-id')
        },
        location: $('.lot__desc-value--location').attr('data-location'),
        price: $('.lot__desc-item--total .lot__desc-value--price').attr('data-price'),
        engine: $('.lot__info-value--engine').attr('data-engine'),
        auction: $('.lot__title').attr('data-auction')
      };
      if (carLot.price) {
        if (carLot.auction == "iaai") {
          $("#auction-2").prop("checked", true);
        }
        $('#calculate__cost').val(carLot.price);
        $('#calculate__eng-volume').val(+carLot.engine * 1000);
        $('.calculate__year .default-select__item[data-year="'+carLot.year+'"]').click();
        let idFuel = +carLot.fuel.id === 1 || +carLot.fuel.id === 2 ? carLot.fuel.id : 4;
        $('.calculate__engine-items .calculate__engine-item[data-fuel-id="'+idFuel+'"]').click();
        let inUSADeliveryEl = $('.calculate__delivery-in-usa .default-select__item[data-name-in="'+carLot.location+'"]'),
        port_id = inUSADeliveryEl.data("port");
        inUSADeliveryEl.click();
        this.autocompletePorts(port_id);
      }

      //automatic set send-arive and change rate on port-arive
      this.form.inUSADeliveryName.el.on("change input propertychange DOMSubtreeModified", e => {
        let $this = $(e.currentTarget),
            port_id = $this.find(".default-select__item--active").data("port");
        this.autocompletePorts(port_id);
      });

      $('#goToCalculator').click(function (e) {
        e.preventDefault();
        const contCalc = $('.calculate__content');
        let wh = $(window).innerHeight();
        let ch = contCalc.innerHeight();
        let top = (+contCalc.position().top + +ch/2) - +wh/2;
        window.scrollTo({top: top, behavior: 'smooth'});
      });
    });

    // ONCLICK CALCULATE

    this.calculateBtn.on("click", () => {
      this.validationFields();
      if (!this.isValid) {
        if (window.innerWidth < 1200) {
          $("html, body").animate({ scrollTop: this.calculatorContainer.find(".calculate__engine").offset().top }, 500);
        }
        return;
      }

      this.totalAmount();
      if (this.rendered) {
        this.calculatorResult.addClass('calculate__result--noborder');

        let marginTop = this.calculatorResult.outerHeight(true) - this.calculatorResult.outerHeight(true);
        $("html, body").animate({ scrollTop: this.calculatorResult.offset().top + marginTop }, 500);
      }
    });

    // ON CHANGE FUEL TYPE

    this.calculatorContainer.on("click", ".calculate__engine-item", e => {
      let $this = $(e.currentTarget);
      $this
        .parent()
        .children()
        .removeClass("calculate__engine-item--active");
      $this.addClass("calculate__engine-item--active");
      $("#calculate__eng-volume")
        .next()
        .html($this.data("fuel-type") == "electro" ? "кВт" : "см3");
      if ($this.data("fuel-type") == "electro") {
        $("#calculate__eng-volume").prop("disabled", true);
        $("#calculate__eng-volume").parent('.calculate__input-engine').addClass('calculate__input-engine--disabled')
      } else {
        $("#calculate__eng-volume").prop("disabled", false);
        $("#calculate__eng-volume").parent('.calculate__input-engine').removeClass('calculate__input-engine--disabled')
      }
    });

    // ON CHANGE FIELDS
    // select item in default-select
    this.calculatorContainer.on("click", ".default-select__item", e => {
      let $this = $(e.currentTarget);
      let value = $this.text();
      let thisSelectBox = $this.parent().parent();
      $this
        .parent()
        .children()
        .removeClass("default-select__item--active");
      $this.addClass("default-select__item--active");
      if (thisSelectBox.hasClass("calculate__delivery-in-usa")) {
        let valueHtml = $this.find(".default-select__item-place").html()+ " " + $this.find(".default-select__item-price").text();
        thisSelectBox
            .find(".default-select__current")
            .html(valueHtml)
            .addClass("default-select__current--active");
      } else if (!thisSelectBox.hasClass("double-select")) {
        thisSelectBox
            .find(".default-select__current")
            .text(value)
            .addClass("default-select__current--active");
      } else {
        $this
            .parent()
            .prev()
            .text(value)
            .addClass("default-select__current--active");
      }
      thisSelectBox
        .find(".default-select__items")
        .removeClass("default-select__items--open");
    });
  }

  //Functions
  validationFields() {
    this.isValid = true;
    this.getDomValues();

    if (this.form.fuelType.value == "electro") {
      this.form.coefYear.value = 1;
      this.form.engineVol.value = 1000;
    }

    Object.keys(this.form).forEach(
        key => {
          let value = this.form[key].value,
              el    = this.form[key].el;

          if ( value && value != null && value != '') {
            el.removeClass('default-input--error');
          } else {
            el.addClass('default-input--error');
            this.isValid = false;

            var valid = (e) => {
              var _value = this.form[e.data.key].getValue();
              if ( _value && _value != null && _value != '') {
                this.form[key].value = _value;
                e.data.el.removeClass('default-input--error');
                e.data.el.off("change input propertychange DOMSubtreeModified", valid);
              }
            };
            el.on("change input propertychange DOMSubtreeModified",{el:el, key:key}, valid);
          }
        }
    );
  }

  autocompletePorts (port_id) {
    let dataFrom;
    $(".calculate__send-arive .default-select__item[data-id-send='"+port_id+"']").click();
    switch (port_id) {
      case 1:
        dataFrom = "price-ny";
        break;
      case 2:
        dataFrom = "price-ga";
        break;
      case 3:
        dataFrom = "price-tx";
        break;
      case 4:
        dataFrom = "price-ca";
        break;
    }
    $(".calculate__port-arive .default-select__item").each(function () {
      let $this = $(this),
          price = "$"+$this.data(dataFrom);
      $this.find(".default-select__item-price").text(price);
      if($this.hasClass("default-select__item--active")) {
        let text = $this.text();
        $this.parent().parent().find(".default-select__current--active").text(text);
      }
    });
  }

  getDomValues () {
    Object.keys(this.form).forEach(key => this.form[key].value = this.form[key].getValue());
  }

  totalAmount() {
    let coefYear = this.form.coefYear.value,
      engineVol = this.form.engineVol.value,
      fuelType = this.form.fuelType.value,
      carPrice = this.form.carPrice.value,
      destPort = this.form.destPort.value,
      inUSADeliveryName = this.form.inUSADeliveryName.value,
        auction = $(".calculate__auction input[name=group-checkbox]:checked").val();

    console.log(coefYear,engineVol,fuelType,carPrice,destPort,inUSADeliveryName);
    // GET EXCISE
    let excise = this.excise(fuelType, coefYear, engineVol);
    console.log("%c Акциз : " + excise, "background: #222; color: #bada55");

    // GET IMPORT DUTY
    let importDuty = this.importDuty(carPrice);
    console.log(
      "%c Пошлина : " + importDuty,
      "background: #222; color: #bada55"
    );

    // GET IMPORT DUTY
    let vat = this.vat(carPrice, excise, importDuty);
    console.log(carPrice, excise, importDuty);
    console.log(fuelType, coefYear, engineVol);
    console.log("%c НДС : " + vat, "background: #222; color: #bada55");

    // GET PENSION FUND
    let pensionFund = this.pensionFund(carPrice);
    console.log(
      "%c Пенсионный фонд : " + pensionFund,
      "background: #222; color: #bada55"
    );

    // GET FROM USA PORT TO NEAR UA PORT
    let { title: portTitle, fromNY, fromGA, fromTX, fromCA, inKiev, ecsped } = this.fromUPortToOurObj( //deliveryFromUSA
      destPort
    );
    // DELIVERY IN USA PORT FROM AUCTION AREA
    let { yarn, priceTo, portId } = this.deliveryInUSAObj(inUSADeliveryName); //deliveryInUSA
    let priceFrom;
    switch (portId) {
      case 1:
        priceFrom = fromNY;
        break;
      case 2:
        priceFrom = fromGA;
        break;
      case 3:
        priceFrom = fromTX;
        break;
      case 4:
        priceFrom = fromCA;
        break;
    }
    console.log(
      "%c Выбраный порт : " +
        portTitle +
        " Из id="+portId+" в порт : " +
        priceFrom +
        " Из порта в Киев : " +
        inKiev +
        " Экспедирование : " +
        ecsped,
      "background: #222; color: #bada55"
    );



    console.log(
      "%c Доставка от : " + yarn + " Доставка в порт id="+portId+" : " + priceTo,
      "background: #222; color: #bada55"
    );

    // DELIVERY IN USA PORT FROM AUCTION AREA
    let auctionPrice = 0;
    if (auction == "iaai") {
      auctionPrice = this.iaaiPricesGenerator(carPrice);
    } else {
      auctionPrice = this.copardPricesGenerator(carPrice);
    }

    console.log(
      "%c Услуги аукциона : " + auctionPrice,
      "background: #222; color: #bada55"
    );

    let { sertifPrice, mreoPrice, caruaPrices } = constantParams;

    let totalPriceOfCar =
      carPrice +
        auctionPrice +
      priceTo +
      priceFrom +
      excise +
      importDuty +
      vat +
      pensionFund +
      inKiev +
      ecsped +
      sertifPrice +
      mreoPrice +
      caruaPrices;

    let objOfParams = {
      carPrice,
      auctionPrice,
      priceTo,
      priceFrom,
      excise,
      importDuty,
      vat,
      pensionFund,
      inKiev,
      ecsped,
      sertifPrice,
      mreoPrice,
      caruaPrices,
      totalPriceOfCar,
    };
    this.resultHTMLGenerate(objOfParams);
    // let primer = document.getElementById('primerInfo');
    // let calculationNotice = document.getElementById('calculation__notice');
    // primer.addEventListener('mouseenter', function () {
    //   console.log('enter');
    //   calculationNotice.classList.add('calculation__notice--open');
    // });
    // primer.addEventListener('mouseleave', function () {
    //   console.log('leave');
    //   calculationNotice.classList.remove('calculation__notice--open');
    // });
  }

  excise(fuelType, coefYear, engineVol) {
    let coefEngine = engineVol / 1000;
    let baseRate = this.baseRate(fuelType, engineVol);
    let excise = this.exchangeEurToUsa(baseRate) * coefEngine * coefYear;
    return +excise.toFixed(2);
  }

  importDuty(carPrice) {
    return +(carPrice * 0.1).toFixed(2);
  }

  vat(carPrice, excise, importDuty) {
    return +((carPrice + excise + importDuty) * 0.2).toFixed(2);
  }

  pensionFund(carPrice) {
    let liveMinUkrUSD = constantParams.liveMinUkrUAH / +this.rate[0].rate; // usd rate
    switch (true) {
      case liveMinUkrUSD * 165 > carPrice:
        console.log(
          "%c Пенсионный фонд (процент) : 0.03",
          "background: #222; color: #bada55"
        );
        return 0.03 * carPrice;
      case liveMinUkrUSD * 165 <= carPrice && liveMinUkrUSD * 290 > carPrice:
        console.log(
          "%c Пенсионный фонд :(процент) : 0.04",
          "background: #222; color: #bada55"
        );
        return 0.04 * carPrice;
      case liveMinUkrUSD * 290 <= carPrice:
        console.log(
          "%c Пенсионный фонд (процент) : 0.05",
          "background: #222; color: #bada55"
        );
        return 0.05 * carPrice;
    }
  }

  baseRate(fuelType, engineVol) {
    switch (fuelType) {
      case "electro":
        return 100;
      case "benzine":
        if (engineVol > 3000) {
          return 100;
        } else {
          return 50;
        }
      case "dizel":
        if (engineVol > 3500) {
          return 150;
        } else {
          return 75;
        }
    }
  }

  fromUPortToOurObj(portID) {
    return deliveryFromUSA.filter(p => p.id == portID)[0];
  }

  deliveryInUSAObj(name) {
    return deliveryInUSA.filter(p => p.yarn == name)[0];
  }

  copardPricesGenerator(carPrice) {
    return copardPrices.reduce((acc, range) => {
      if (between(carPrice, range.from, range.to)) {
        if (hasProperty(range, "percent")) {
          return acc + (carPrice * range.percent) + range.service + range.stavka;
        }
        return acc + range.price;
      }
      return acc;
    }, 0);
  }

  iaaiPricesGenerator(carPrice) {
    return iaaiPrices.reduce((acc, range) => {
      if (between(carPrice, range.from, range.to)) {
        if (hasProperty(range, "percent")) {
          return acc + (carPrice * range.percent) + range.price;
        }
        return acc + range.price;
      }
      return acc;
    }, 0);
  }

  generateYearAndCoeficient() {
    let currentYear = new Date().getFullYear() + 1;
    let allYearsAndCoef = [];
    for (let i = -2; i <= 15; i++) {
      i > 1
        ? allYearsAndCoef.push({ year: currentYear--, coef: i })
        : allYearsAndCoef.push({ year: currentYear--, coef: 1 });
    }
    // console.log("%c YearAndCoeficient : ", "background: #222; color: #bada55");
    // console.log(allYearsAndCoef);
    this.generateHTMLYearCoef(allYearsAndCoef);
  }
  generateHTMLYearCoef(allYearsAndCoef) {
    let HTLMContainer = "";
    let line = "";
    allYearsAndCoef.forEach((obj, i) => {
      if (allYearsAndCoef.length == i + 1) {
        line = `<div class="default-select__item" data-value="${obj.coef}" data-year="${obj.year}">от ${
          obj.year
        }</div>`;
      } else {
        line = `<div class="default-select__item" data-value="${obj.coef}" data-year="${obj.year}">${
          obj.year
        }</div>`;
      }
      HTLMContainer = HTLMContainer + line;
    });
    $(".calculate__year .default-select__items").html(HTLMContainer);
  }

  getCurrRate() {
    const nbuAPI =
      "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json";
      // "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json";
    const nbuAPI_usd =
      "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json&valcode=USD";
    const nbuAPI_eur =
      "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json&valcode=EUR";
    const privatAPI =
      "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=3";
    let rate = [], ratePrivat = [] ;
    Promise.all([$.getJSON(nbuAPI_usd),$.getJSON(nbuAPI_eur)]).then(d => {
      this.rate = d.map(el => el[0]);
      if (!this.rate.every(item => !!item)) {
        $.getJSON(nbuAPI, function(json) {
          rate = json.filter(c => c.cc == "USD" || c.cc == "EUR");
        }).done(() => {
          if (rate.filter(c => c.cc == "EUR").length > 0) {
            this.rate = rate;
          } else {
            $.getJSON(privatAPI, function(json) {
              ratePrivat = json.filter(c => c.ccy == "USD" || c.ccy == "EUR");
            }).done(() => {
              this.rate = ratePrivat.map(c => {
                return {cc: c.ccy, rate: c.ccy == "EUR"?c.sale:c.buy}
              });
            });
          }
        });
      }
    });
    // $.getJSON(nbuAPI, function(json) {
    //   rate = json.filter(c => c.cc == "USD" || c.cc == "EUR");
    // }).done(() => {
    //   this.rate = rate;
    //   console.log(this.rate);
    // });
  }

  exchangeEurToUsa(amount) {
    let usdRate = +this.rate.filter(c => c.cc == "USD")[0].rate;
    let eurRate = +this.rate.filter(c => c.cc == "EUR")[0].rate;
    return (amount * eurRate) / usdRate;
  }

  deliveryFromHTMLGenerate() {
    let deliveryFromUSAarr = deliveryFromUSA.map(
      p => `<div class="default-select__item" data-id-from="${p.id}" 
data-price-ny="${p.fromNY}" 
data-price-ga="${p.fromGA}" 
data-price-tx="${p.fromTX}" 
data-price-ca="${p.fromCA}">
    <span class="default-select__item-place">${p.title}</span>
    <span class="default-select__item-price"></span>
    </div>`
    );
    let deliverySendUSAarr = deliverySendUSA.map(
      // <span class="default-select__item-price">$${p.fromNY}</span>
      p => `<div class="default-select__item" data-id-send="${p.id}">
    <span class="default-select__item-place">${p.title}</span>
    </div>`
    );
    $(".calculate__port-arive .default-select__items").html(
      deliveryFromUSAarr.join("")
    );
    $(".calculate__send-arive .default-select__items").html(
      deliverySendUSAarr.join("")
    );
  }

  deliveryInHTMLGenerate() {
    let deliveryInUSAarr = deliveryInUSA.map(
      p => {
        let port;
        switch (p.portId) {
          case 1: port = "NY";
            break;
          case 2: port = "GA";
            break;
          case 3: port = "TX";
            break;
          case 4: port = "CA";
            break;
        }
        return`<div class="default-select__item" data-id-in="${
          p.id
          }" data-name-in="${p.yarn}" data-port="${p.portId}">
    <span class="default-select__item-place">${p.yarn} 
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="8" viewBox="0 0 10 8"><defs><path id="8cpma" d="M1859.764 40.44l-3.292-3.196a.822.822 0 0 0-.786-.22.795.795 0 0 0-.579.562.768.768 0 0 0 .225.764l1.946 1.887h-6.472a.795.795 0 0 0-.806.783c0 .432.36.782.806.782h6.454l-1.928 1.876a.767.767 0 0 0 .014 1.093c.31.3.809.306 1.126.013l3.292-3.197a.766.766 0 0 0 .236-.593.773.773 0 0 0-.236-.553z"/></defs><use fill="#70e0a3" xlink:href="#8cpma" transform="translate(-1850 -37)"/></svg>
${port}</span>
    <span class="default-select__item-price">$${p.priceTo}</span>
    </div>`}
    );
    $(".calculate__delivery-in-usa .default-select__items").html(
      deliveryInUSAarr.join("")
    );
  }

  resultHTMLGenerate(objOfParams) {
    let popap;
    let openPopup = `
    popap = document.querySelector('.main-popup');
    popap.classList.remove('main-popup--hide');
    `;
    $(".calculate__result").html(`
      <div class="calculation">
            <h2 class="second-title second-title--left calculation__title">ПРИМЕРНЫЙ РАСЧЕТ СТОИМОСТИ</h2>
            <div class="calculation__content">
                <div class="calculation__item">
                <div class="calculation__name">Стоимость авто в США:</div>
                <div class="calculation__value">$${objOfParams.carPrice.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Аукционный сбор:</div>
                <div class="calculation__value">$${
                  objOfParams.auctionPrice.toFixed(2)
                }</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Доставка по США:</div>
                <div class="calculation__value">$${objOfParams.priceTo.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Доставка из США:</div>
                <div class="calculation__value">$${objOfParams.priceFrom.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Акциз:</div>
                <div class="calculation__value">$${objOfParams.excise.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Пошлина:</div>
                <div class="calculation__value">$${objOfParams.importDuty.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">НДС:</div>
                <div class="calculation__value">$${objOfParams.vat.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Пенсионный фонд:</div>
                <div class="calculation__value">$${
                  objOfParams.pensionFund.toFixed(2)
                }</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Экспедирование авто:</div>
                <div class="calculation__value">$${objOfParams.ecsped.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Доставка из порта в Киев:</div>
                <div class="calculation__value">$${objOfParams.inKiev.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Сертификация:</div>
                <div class="calculation__value">$${
                  objOfParams.sertifPrice.toFixed(2)
                }</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Услуги сервисного центра (МРЭО):</div>
                <div class="calculation__value">$${objOfParams.mreoPrice.toFixed(2)}</div>
                </div>
                <div class="calculation__item">
                <div class="calculation__name">Стоимость наших услуг от:</div>
                <div class="calculation__value">$${
                  objOfParams.caruaPrices.toFixed(2)
                }</div>
                </div>
            </div>
            <div class="calculation__footer">
                <div class="calculation__name">Итоговая <div id="primerInfo" class="calculation__link">примерная<div id="calculation__notice" class="calculation__notice" tabindex="-1">
                <p class="white-text calculation__notice-desc">Для получения максимально точного и актуального расчета, обращайтесь к нашим менеджерам.</p>
                <a href="javascript:${openPopup}" class="call-link calculation__call-link show-popup">СВЯЗАТЬСЯ С МЕНЕЖЕРОМ</a>
                </div></div> стоимость</div>
                
                <div class="calculation__value calculation__value--total">$${
                  objOfParams.totalPriceOfCar.toFixed(2)
                }</div>
            </div>
            </div>`);
    this.rendered = true;
  }
}

export default Calculator;
