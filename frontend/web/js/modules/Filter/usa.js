import $ from "jquery";
import { paginationOutput } from "./pagination";
import * as slider from "./slider";
import { textTruncate, formatMoney }  from "../utils";

let isSpinnerVisible = false;

function getFilters(params) {
  let filters = $.ajax({
    type: "GET",
    url:
      // caruaData.root_url +
      caruaData.parser_url + "/api/v1/auction/filter",
  });
  return filters.done((data, textStatus, request) => {
    const obj = JSON.stringify(data);
    const results = $.parseJSON(obj);
    createHTMLFilters(results, params);
  });
}

function createHTMLFilters(results, params) {
  const usaBrand = $('.filter-sidebar__content--usa [data-select="brand"]');
  const usaModel = $(".filter-sidebar__content--usa [data-select='model']");
  const usaYearMin = $(
    '.filter-sidebar__content--usa [data-select="year_min"]'
  );
  const usaYearMax = $(
    ".filter-sidebar__content--usa [data-select='year_max']"
  );
  const usaBodyStyle = $(
    ".filter-sidebar__content--usa [data-select='body_style']"
  );
  const usaTransmission = $(
    ".filter-sidebar__content--usa [data-select='transmission']"
  );
  const usaFluel = $(".filter-sidebar__content--usa [data-select='fluel']");
  const usaEngine = $(".filter-sidebar__content--usa [data-select='engine']");
  const usaDriveUnit = $(
    ".filter-sidebar__content--usa [data-select='drive_unit']"
  );
  const usaLocation = $(
    ".filter-sidebar__content--usa [data-select='location']"
  );
  const usaDamage = $(".filter-sidebar__content--usa [data-select='damage']");

  usaBrand.html(`
        ${results.brand
          .map(
            brand => `
          <div class="default-select__item" data-value="${brand.id}">${
              brand.title
            }</div>
          `
          )
          .join("")}`);
  if (params.brand && params.brand != "all") {
    // TO DO MAKE FILTER
    let index = params.brand;
    usaModel.html(`
            ${results.brand.find(el => {
              if ( el.id == index) {
                return true;
              }
              return false;
    }).models.map(
                model => `
              <div class="default-select__item" data-value="${model.id}">${
                  model.title
                }</div>
              `
              )
              .join("")}`);
  }
  if (results.year.min.length > 0 && results.year.max.length > 0) {
    let allYears = [];
    let yearMin = [];
    let yearMax = [];
    for (let i = +results.year.min; i <= +results.year.max; i++) {
      if (params.year_min > 0 && params.year_min <= i) {
        yearMax.push(i);
      }
      if (params.year_max > 0 && params.year_max >= i) {
        yearMin.push(i);
      }
      allYears.push(i);
    }
    yearMax.sort((a, b) => b - a);
    yearMin.sort((a, b) => b - a);
    allYears.sort((a, b) => b - a);
    usaYearMin.html(`
          ${(yearMin.length > 0 ? yearMin : allYears)
            .map(
              year => `
            <div class="default-select__item" data-value="${year}">${year}</div>
            `
            )
            .join("")}`);
    usaYearMax.html(`
    ${(yearMax.length > 0 ? yearMax : allYears)
      .map(
        year => `
              <div class="default-select__item" data-value="${year}">${year}</div>
              `
      )
      .join("")}`);
  }
  if (results.bodyStyle) {
    checkboxGenerate(usaBodyStyle, results.bodyStyle);
  }
  if (results.transmission) {
    checkboxGenerate(usaTransmission, results.transmission);
  }
  if (results.fuel) {
    checkboxGenerate(usaFluel, results.fuel);
  }
  if (results.engine) {
    checkboxGenerate(usaEngine, results.engine);
  }
  if (results.drive) {
    checkboxGenerate(usaDriveUnit, results.drive);
  }
  if (results.damage) {
    checkboxGenerate(usaDamage, results.damage);
  }
  if (results.yard) {
    checkboxGenerate(usaLocation, results.yard);
  }
}

function getResults(params, dictionary) {
  isSpinnerVisible = true;
  const resultsDiv = $(".lot-filter__main-content");
  const paginationContiner = $(".pagination");
  if (isSpinnerVisible) {
    resultsDiv.html(`<div class="lot-filter__spinner">
      <div class="preloader__items">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="65" height="85" viewBox="0 0 230 400"><defs><path id="dsmza" d="M1554.604 344.152L1721.732 177 1771 226.271 1620.29 377 1771 527.733 1721.736 577l-167.132-167.153c-18.139-18.142-18.139-47.552 0-65.695"/></defs><g><g transform="translate(-1541 -177)"><use fill="#4d49be" xlink:href="#dsmza"/></g></g></svg>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="65" height="85" viewBox="0 0 230 400"><defs><path id="dsmza" d="M1554.604 344.152L1721.732 177 1771 226.271 1620.29 377 1771 527.733 1721.736 577l-167.132-167.153c-18.139-18.142-18.139-47.552 0-65.695"/></defs><g><g transform="translate(-1541 -177)"><use fill="#4d49be" xlink:href="#dsmza"/></g></g></svg>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="65" height="85" viewBox="0 0 230 400"><defs><path id="dsmza" d="M1554.604 344.152L1721.732 177 1771 226.271 1620.29 377 1771 527.733 1721.736 577l-167.132-167.153c-18.139-18.142-18.139-47.552 0-65.695"/></defs><g><g transform="translate(-1541 -177)"><use fill="#4d49be" xlink:href="#dsmza"/></g></g></svg>
      </div>
    </div>`);
    paginationContiner.html("");
  }

  let obj = {
    "pagination[limit]": 9,
    "pagination[page]": params.currentPage,
  };
  if (params.brand && params.brand != "all") {
    obj["filters[brand][id]"] = params.brand;
  }
  if (params.model && params.model != "all") {
    obj["filters[model][id]"] = params.model;
  }
  if (params.auction_type && params.auction_type.length > 0) {
    obj["filters[auctionType][]"] = params.auction_type;
  }
  if (params.year_min && params.year_min > 0) {
    obj["filters[year][GREAT_THAN_OR_EQUAL]"] = params.year_min;
  }
  if (params.year_max && params.year_max > 0) {
    obj["filters[year][LESS_THAN_OR_EQUAL]"] = params.year_max;
  }
  if (params.price_min_max[0] && params.price_min_max[0] > 0) {
    obj["filters[high_bit][GREAT_THAN_OR_EQUAL]"] = params.price_min_max[0];
  }
  if (params.price_min_max[1] && params.price_min_max[1] > 0) {
    obj["filters[high_bit][LESS_THAN_OR_EQUAL]"] = params.price_min_max[1];
  }
  if (params.body_style && params.body_style.length > 0) {
    obj["filters[bodyStyle][id][IN][]"] = params.body_style;
  }
  if (params.transmission && params.transmission.length > 0) {
    obj["filters[transmission][id][IN][]"] = params.transmission;
  }
  if (params.fluel && params.fluel.length > 0) {
    obj["filters[fuel][id][IN][]"] = params.fluel;
  }
  if (params.drive_unit && params.drive_unit.length > 0) {
    obj["filters[drive][id][IN][]"] = params.drive_unit;
  }
  if (params.engine && params.engine.length > 0) {
    obj["filters[engine][]"] = params.engine;
  }
  if (params.damage && params.damage.length > 0) {
    obj["filters[damage][id][IN][]"] = params.damage;
  }
  if (params.location && params.location.length > 0) {
    obj["filters[yard][id][IN][]"] = params.location;
  }
  if (params.order && params.orderby === "lot_ua_price") {
    obj["orders[high_bit]"] = params.order;
  }
  if (params.order && params.orderby === "lot_ua_year") {
    obj["orders[year]"] = params.order;
  }

  let init = $.ajax({
    type: "GET",
    url: caruaData.parser_url + "/api/v1/auction/vehicle",
    data: $.param(obj),
  });

  return init.done((data, textStatus, request) => {
    const count = request.getResponseHeader("Pagination-Count");
    const paglimit = request.getResponseHeader("Pagination-Limit");
    const obj = JSON.stringify(data);
    const results = $.parseJSON(obj);
    const pagescount = Math.ceil(count / paglimit);
    slider.updateData(results);
    isSpinnerVisible = false;
    createHTML(results, count, pagescount, params.currentPage);
    dictionary.lotsCountry = [{ id: "usa", title: "В США" }];
    activeFiltersOutputHTML(params, dictionary);
  });
}

function activeFiltersOutputHTML(params, dictionary) { //need fix here
  const activeFiltersParent = $(".your-filters");
  const activeFiltersDiv = $(".your-filters__content");
  let paramsKeyArray = Object.keys(params);
  let activeFilters = paramsKeyArray.reduce((acc, curr) => {
    if (
      curr != "currentPage" &&
      curr != "order" &&
      curr != "orderby" &&
      curr != "auction_type" &&
      params[curr] != "all" &&
      params[curr]
    ) {
      let htmlFilter = "";
      if (Array.isArray(params[curr])) {
        let compareVoc = {
          body_style: "bodyStyle",
          transmission: "transmission",
          fluel: "fuel",
          drive_unit: "drive",
          damage: "damage",
          location: "yard",
          brand: "brand",
          model: "model",
          auction_type: "auctionType",
        };
        switch (curr) {
          case "price_min_max":
            //   let obj = dictionary.year.filter(f => f.id === params[curr])[0];
            params[curr].forEach((f, i) => {
              htmlFilter = htmlFilter + activeFiltersHTMLGenerator(curr, f, i);
            });
            break;
          case "engine":
            //   let obj = dictionary.year.filter(f => f.id === params[curr])[0];

            params[curr].forEach(f => {
              htmlFilter = htmlFilter + activeFiltersHTMLGenerator(curr, f);
            });
            break;
          default:
            params[curr].forEach((f, i) => {
              let obj = dictionary[compareVoc[curr]].filter(i => f == i.id)[0];
              htmlFilter =
                htmlFilter + activeFiltersHTMLGenerator(curr, obj, i);
            });
        }
        // console.log(htmlFilter);
      } else {
        // console.log(params[curr], curr, dictionary[curr]);
        if (Array.isArray(dictionary[curr])) {
          let obj = dictionary[curr].filter(f => f.id == params[curr])[0];
          htmlFilter = activeFiltersHTMLGenerator(curr, Object.assign({}, obj));
        } else {
          if (curr == "model") {
            let currBrand = dictionary.brand.filter(
              f => f.id == params.brand
            )[0];
            let arrayOfModels = currBrand.models.filter(
              m => m.id == params[curr]
            )[0];
            htmlFilter = activeFiltersHTMLGenerator(curr, arrayOfModels);
          } else {
            // console.log("ELSE",dictionary[params[curr]]);
            // console.log("ELSE",params[curr]);
            htmlFilter = activeFiltersHTMLGenerator(
              curr,
              params[curr],
              dictionary[params[curr]]
            );
          }
        }
      }
      if (htmlFilter) {
        return acc.concat(htmlFilter);
      } else {
        return acc;
      }
    } else {
      return acc;
    }
  }, []);

  activeFiltersDiv.html(activeFilters.join(""));
  if(activeFilters.length > 1){
    activeFiltersParent.show();
  }else{
    activeFiltersParent.hide();
  }
}

function activeFiltersHTMLGenerator(p, obj, i, d) {
  if (obj === "ua_currency" || obj === "usa_currency") return ""; //currency filter off
  switch (p) {
    case "price_min_max":
      return `<div class="your-filters__item">
      ${!i ? "Цена от " + obj : "Цена до " + obj}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${obj}" data-filter-side="${!i ? "min" : "max"}"/>
    </div>`;
    case "year_max":
      return `<div class="your-filters__item">
      Год до ${obj}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${obj}" />
    </div>`;
    case "year_min":
      return `<div class="your-filters__item">
      Год от ${obj}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${obj}" />
    </div>`;
    case "lotsCountry":
      return `<div class="your-filters__item">
      ${obj.title}
    </div>`;
    case "engine":
      return `<div class="your-filters__item">
    ${obj}
    <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${obj}" />
  </div>`;
    default:
      // console.log("DEFAULT",p, obj, i, d);
      return `<div class="your-filters__item">
      ${obj.title}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${
        obj.id
      }" />
    </div>`;
  }
}

function createHTML(results, count, pagescount, currentPage) {
  const countContainer = $(".lot-filter__result strong");
  countContainer.html(count);
  paginationOutput(pagescount, currentPage);
  const currency = $(
    '.filter-sidebar__content--usa [data-select="currency"] .default-select__item--active'
  ).data("value");
  if (currency != "usa_currency") {
    const nbuAPI =
      "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=USD&date=20190504&json";
    let rate;
    $.getJSON(nbuAPI, function(json) {
      rate = json[0].rate;
    }).done(function() {
      htmlGenerator(results, count, currency, rate);
    });
  } else {
    htmlGenerator(results, count, currency);
  }
}

function htmlGenerator(data, count, currency, rate) {
  const resultsDiv = $(".lot-filter__main-content");
  const pagination = $(".lot-filter__main .pagination");
  resultsDiv.html(`
    ${count ? "" : "<p>Лотов с такими параметрами не найдено</p>"}
        ${data
          .map(
            lots =>`
            <div
              class="photo-card photo-card--filter"
              data-url="/lotusa/${lots.id}"
            >
              <div class="photo-card__wrap">
                <div
                  class="photo-card__pic"
                  style="background-image: url('${lots.images[0].preview}');"
                >
                  <div class="photo-card__pic-total" data-id="${lots.id}">
                    <span class="photo-card__pic-count">
                      ${lots.images.length}
                    </span>
                    <span class="photo-card__pic-text">смотреть фото</span>
                  </div>
                </div>
                <div class="photo-card__info">
                  <h3 class="photo-card__title">${lots.brand.title} ${
              lots.model_detail
            } ${lots.year}</h3>
                  <div class="photo-card__info-item">
                    <span class="photo-card__info-title">Локация</span>
                    <span class="photo-card__info-value">
                       ${ lots.yard ? textTruncate(lots.yard.title) : "Уточняется"}
                    </span>
                  </div>
                  <div class="photo-card__info-item">
                    <span class="photo-card__info-title">Дата торгов</span>
                    <span class="photo-card__info-value">${
                      lots.auction_date ? lots.auction_date : "Уточняется"
                    }</span>
                  </div>
                  <div class="photo-card__info-item">
                    <span class="photo-card__info-title">Повреждение</span>
                    <span class="photo-card__info-value">
                      ${lots.damage ? textTruncate(lots.damage.title) : "Уточняется"}
                    </span>
                  </div>
                </div>
              </div>
              <div class="photo-card__info-item photo-card__info-item--total">
                <span class="photo-card__info-title">Текущая ставка</span>
                <span class="photo-card__info-value photo-card__info-value--total">
                ${ lots.high_bit == 0 || lots.high_bit == undefined ? "Уточняется" :
                  currency == "usa_currency"
                    ? "$" + formatMoney(lots.high_bit, 0, ".", " ")
                    : "&#8372;" + formatMoney(lots.high_bit * rate, 0, ".", " ")
                }
                </span>
              </div>
            </div>
          `
          )
          .join("")}`);
  if (count > 0) {
    pagination.show();
  }else{
    pagination.hide();
  }
}

function checkboxGenerate(target, obj) {
  //   console.log(obj);
  target.html(`
      ${(Array.isArray(obj) ? obj : Object.keys(obj))
        .map(
          item => `
            <label for="${
              typeof item === "object" ? item.title : item
            }" class="default-checkbox">
            <input type="checkbox" id="${
              typeof item === "object" ? item.title : item
            }" value="${
            typeof item === "object" ? item.id : item
          }" class="default-checkbox__check">
            <span class="default-checkbox__title">${
              Array.isArray(obj)
                ? typeof item === "object"
                  ? item.title
                  : item
                : obj[item]
            }</span>
          </label>
          `
        )
        .join("")}`);
}

export { getFilters, getResults };
