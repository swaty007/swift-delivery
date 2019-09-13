import $ from "jquery";
import NoUISlider from "nouislider";

import { paginationOutput } from "./pagination";
import { formatMoney, checkboxGenerate } from "../utils";
import * as slider from "./slider";

let isSpinnerVisible = false;

function getFilters(params) {
  // console.log(params);
  const searchUrl =
    caruaData.root_url +
    "/wp-json/lots/v1/filter?brand=" +
    params.brand +
    "&model=" +
    params.model +
    "&year_min=" +
    params.year_min +
    "&year_max=" +
    params.year_max;
  let filters = $.ajax({
    type: "GET",
    url: encodeURI(searchUrl),
  });
  filters.done((data, textStatus, request) => {
    const obj = JSON.stringify(data);
    const results = $.parseJSON(obj);
    // this.filterArray = results;
    createHTMLFilters(results);
    // console.log(results);
  });
}

function createHTMLFilters(results) {
  //   console.log(results);
  //   const uaBrand = $(".filter-sidebar__content--ua").find(`[data-slide='${current}']`);
  const uaBrand = $('.filter-sidebar__content--ua [data-select="brand"]');
  const uaModel = $(".filter-sidebar__content--ua [data-select='model']");
  const uaYearMin = $('.filter-sidebar__content--ua [data-select="year_min"]');
  const uaYearMax = $(".filter-sidebar__content--ua [data-select='year_max']");
  const uaBodyStyle = $(
    ".filter-sidebar__content--ua [data-select='body_style']"
  );
  const uaTransmission = $(
    ".filter-sidebar__content--ua [data-select='transmission']"
  );
  const uaFluel = $(".filter-sidebar__content--ua [data-select='fluel']");
  const uaEngine = $(".filter-sidebar__content--ua [data-select='engine']");
  const uaDriveUnit = $(".filter-sidebar__content--ua [data-select='drive_unit']");
  uaBrand.html(`${Object.keys(results.filterUABrand).map(bS => `<div class="default-select__item" data-value="${bS}">${bS}</div>`).join("")}`);
  if (results.filterUAModel.length > 0) {
    uaModel.html(`
          ${results.filterUAModel
            .map(
              brand => `
            <div class="default-select__item" data-value="${brand}">${brand}</div>
            `
            )
            .join("")}`);
  }
  if (
    results.filterUAYears.min.length > 0 &&
    results.filterUAYears.max.length > 0
  ) {
    results.filterUAYears.min.sort((a, b) => b - a);
    results.filterUAYears.max.sort((a, b) => b - a);
    uaYearMin.html(`
          ${results.filterUAYears.min
            .map(
              year => `
            <div class="default-select__item" data-value="${year}">${year}</div>
            `
            )
            .join("")}`);
    uaYearMax.html(`
            ${results.filterUAYears.max
              .map(
                year => `
              <div class="default-select__item" data-value="${year}">${year}</div>
              `
              )
              .join("")}`);
  }
  if (results.filterUABodyStyle) {
    checkboxGenerate(uaBodyStyle, results.filterUABodyStyle);
  }
  if (results.filterUATransmission) {
    checkboxGenerate(uaTransmission, results.filterUATransmission);
  }
  if (results.filterUAFluel) {
    checkboxGenerate(uaFluel, results.filterUAFluel);
  }
  if (results.filterUAEngine) {
    checkboxGenerate(uaEngine, results.filterUAEngine);
  }
  if (results.filterUADriveUnit) {
    checkboxGenerate(uaDriveUnit, results.filterUADriveUnit);
  }
}

function getResults(params) {
  //   console.log(params);
  const resultsDiv = $(".lot-filter__main-content");
  const paginationContiner = $(".pagination");

  if (!isSpinnerVisible) {
    resultsDiv.html(`<div class="lot-filter__spinner">
      <div class="preloader__items">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="65" height="85" viewBox="0 0 230 400"><defs><path id="dsmza" d="M1554.604 344.152L1721.732 177 1771 226.271 1620.29 377 1771 527.733 1721.736 577l-167.132-167.153c-18.139-18.142-18.139-47.552 0-65.695"/></defs><g><g transform="translate(-1541 -177)"><use fill="#4d49be" xlink:href="#dsmza"/></g></g></svg>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="65" height="85" viewBox="0 0 230 400"><defs><path id="dsmza" d="M1554.604 344.152L1721.732 177 1771 226.271 1620.29 377 1771 527.733 1721.736 577l-167.132-167.153c-18.139-18.142-18.139-47.552 0-65.695"/></defs><g><g transform="translate(-1541 -177)"><use fill="#4d49be" xlink:href="#dsmza"/></g></g></svg>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="65" height="85" viewBox="0 0 230 400"><defs><path id="dsmza" d="M1554.604 344.152L1721.732 177 1771 226.271 1620.29 377 1771 527.733 1721.736 577l-167.132-167.153c-18.139-18.142-18.139-47.552 0-65.695"/></defs><g><g transform="translate(-1541 -177)"><use fill="#4d49be" xlink:href="#dsmza"/></g></g></svg>
      </div>
    </div>`);
    paginationContiner.html("");
    isSpinnerVisible = true;
  }

  const searchUrl =
    caruaData.root_url +
    "/wp-json/lots/v1/search?paged=" +
    params.currentPage +
    "&brand=" +
    params.brand +
    "&model=" +
    params.model +
    "&year_min=" +
    params.year_min +
    "&year_max=" +
    params.year_max +
    "&body_style=" +
    params.body_style +
    "&transmission=" +
    params.transmission +
    "&fluel=" +
    params.fluel +
    "&drive_unit=" +
    params.drive_unit +
    "&engine=" +
    params.engine +
    "&price_min_max=" +
    params.price_min_max +
    "&order=" +
    params.order +
    "&orderby=" +
    params.orderby;
  //   console.log(encodeURI(searchUrl));
  let init = $.ajax({
    type: "GET",
    url: encodeURI(searchUrl),
  });
  return init.done((data, textStatus, request) => {
    const obj = JSON.stringify(data);
    const results = $.parseJSON(obj);
    slider.updateData(results["lotsUAData"]);
    createHTML(results, params);
    // initRangePrice(results.lotsUAMaxPrice, results.lotsUAMinPrice, params);
  });
}

// function initRangePrice(max, min, params) {
//   let priceRange = $("#price-range")[0];
//     priceRange.noUiSlider.updateOptions({
//       range: {
//         min,
//         max,
//       },
//     });
// }

function createHTML(results, params) {
  const countContainer = $(".lot-filter__result strong");

  countContainer.html(results.lotsUACount);
  paginationOutput(results.lotsUAPagination, params.currentPage);
  const currency = $(
    '.filter-sidebar__content--ua [data-select="currency"] .default-select__item--active'
  ).data("value");
  if (currency != "usa_currency") {
    const nbuAPI =
      "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=USD&date=20190504&json";
    var rate;
    $.getJSON(nbuAPI, function(json) {
      rate = json[0].rate;
    }).done(function() {
      htmlGenerator(results.lotsUAData, currency, rate);
    });
  } else {
    htmlGenerator(results.lotsUAData, currency);
  }
  activeFiltersOutputHTML(params, results.lotsUADictionary);

  isSpinnerVisible = false;
}

function activeFiltersOutputHTML(params, dictionary) {
  const activeFiltersParent = $(".your-filters");
  const activeFiltersDiv = $(".your-filters__content");
  let paramsKeyArray = Object.keys(params);
  let activeFilters = paramsKeyArray.reduce((acc, curr) => {
    if (
      curr != "currentPage" &&
      curr != "order" &&
      curr != "orderby" &&
      params[curr] != "all" &&
      params[curr]
    ) {
      let htmlFilter = "";
      if (Array.isArray(params[curr])) {
        params[curr].forEach((f, i) => {
          htmlFilter =
            htmlFilter + activeFiltersHTMLGenerator(curr, f, dictionary[f], i);
        });
        // console.log(htmlFilter);
      } else {
        htmlFilter = activeFiltersHTMLGenerator(
          curr,
          params[curr],
          dictionary[params[curr]]
        );
        // console.log(htmlFilter);
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
  //   if (
  //     $(".your-filters__content").children().length > 1 &&
  //     !$(".your-filters__cancel")[0]
  //   ) {
  //     $(".your-filters").append(
  //       '<div class="your-filters__cancel">СБРОСИТЬ ФИЛЬТРЫ</div>'
  //     );
  //   }
}

function activeFiltersHTMLGenerator(p, c, d, i) {
  if (c === "ua_currency" || c === "usa_currency") return ""; //currency filter off
  switch (p) {
    case "price_min_max":
      return `<div class="your-filters__item">
      ${!i ? "Цена от " + c : "Цена до " + c}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${c}" />
    </div>`;
    case "year_max":
      return `<div class="your-filters__item">
      Год до ${c}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${c}" />
    </div>`;
    case "year_min":
      return `<div class="your-filters__item">
      Год от ${c}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${c}" />
    </div>`;
    case "lotsCountry":
      return `<div class="your-filters__item">
      ${d}
    </div>`;
    case "engine":
      return `<div class="your-filters__item">
      ${c}
    </div>`;
    default:
      return `<div class="your-filters__item">
      ${d}
      <div class="your-filters__item-remove" data-filter="${p}" data-filter-val="${c}" />
    </div>`;
  }
}

function htmlGenerator(data, currency, rate) {
  const resultsDiv = $(".lot-filter__main-content");
  const pagination = $(".lot-filter__main .pagination");
  resultsDiv.html(`
          ${data.length ? "" : "<p>Лотов с такими параметрами не найдено</p>"}
              ${data
                .map(
                  lots => `
                  <div
                    class="photo-card photo-card--filter"
                    data-url="${lots.permalink}"
                  >
                    <div class="photo-card__wrap">
                      <div
                        class="photo-card__pic"
                        style="background-image: url('${lots.images[0]}');"
                      >
                        <div class="photo-card__pic-total" data-id="${lots.id}">
                          <span class="photo-card__pic-count">
                            ${lots.images_count}
                          </span>
                          <span class="photo-card__pic-text">смотреть фото</span>
                        </div>
                      </div>
                      <div class="photo-card__info">
                        <h3 class="photo-card__title">${lots.title}</h3>
                        <div class="photo-card__info-item">
                          <span class="photo-card__info-title">Пробег</span>
                          <span class="photo-card__info-value">
                            ${lots.odometer}
                          </span>
                        </div>
                        <div class="photo-card__info-item">
                          <span class="photo-card__info-title">Двигатель</span>
                          <span class="photo-card__info-value">${
                            lots.engine
                          }</span>
                        </div>
                        <div class="photo-card__info-item">
                          <span class="photo-card__info-title">Коробка</span>
                          <span class="photo-card__info-value">
                            ${lots.transmission}
                          </span>
                        </div>
                        <div class="photo-card__info-item">
                          <span class="photo-card__info-title">Статус</span>
                          <span class="photo-card__info-value">
                            ${lots.customs_cleared}
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="photo-card__info-item photo-card__info-item--total">
                      <span class="photo-card__info-title">Оценка</span>
                      <span class="photo-card__info-value photo-card__info-value--total">
                        ${
                          currency == "usa_currency"
                            ? "$" + formatMoney(lots.price, 0, ".", " ")
                            : "&#8372;" +
                              formatMoney(lots.price * rate, 0, ".", " ")
                        }
                      </span>
                    </div>
                  </div>
                `
                )
                .join("")}`);
  if (data.length > 0) {
    pagination.show();
  }else{
    pagination.hide();
  }
}

export { getResults, getFilters };
