import $ from "jquery";
import NoUISlider from "nouislider";

import * as usa from "./usa";
import * as ua from "./ua";

const searchQueryInit = {
  lotsCountry: "ua",
  brand: "all",
  model: "all",
  auction_type: [],
  currentPage: 1,
  year_max: 0,
  year_min: 0,
  body_style: [],
  engine: [],
  transmission: [],
  fluel: [],
  drive_unit: [],
  price_min_max: [],
  order: "desc",
  orderby: "lot_ua_year",
  damage: [],
  location: [],
};

// const currentYear = new Date().getFullYear();
class Filter {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    this.paginationContiner = $(".pagination");
    this.filterContainer = $(".filter-sidebar");
    this.usaLotsChange = $("[data-id='tabUSA']");
    this.uaLotsChange = $("[data-id='tabUA']");
    this.priceRange = document.getElementById("price-range");
    this.priceRangeUSA = document.getElementById("price-range-usa");
    this.priceRangeUSAInit = false;
    this.priceRangeUAInit = false;
    this.firstStart = true;
    this.usaDictionary = {};
    this.searchQuery = {
      lotsCountry: "ua",
      brand: "all",
      model: "all",
      auction_type: [],
      currentPage: 1,
      year_max: 0,
      year_min: 0,
      body_style: [],
      engine: [],
      transmission: [],
      fluel: [],
      drive_unit: [],
      price_min_max: [],
      order: "desc",
      orderby: "lot_ua_year",
      damage: [],
      location: [],
    };
    if ($(".lot-filter")[0]) {
      this.events();
    }
    // this.isSpinnerVisible  false;
  }

  // 2. events
  events() {
    if (!this.firstStart) {
      this.firstStart = false;
      return;
    }
    // ONLOAD
    $(window).one("load", () => {
      this.linkFilter();
      //clicks cards
      $(document).on("click",".photo-card", (e) => {
        e.preventDefault();
        this.makeUrlData(true);
        window.location = $(e.currentTarget).attr('data-url');
      });
      //refresh event
        $(document).keydown( (e) => {
            if ((e.which || e.keyCode) == 116) {
                e.preventDefault();
                this.makeUrlData(true);
                location.reload();
            }
        });

        if (this.searchQuery.lotsCountry === "ua") {
        $(".filter-sidebar__tabs-item").removeClass(
          "filter-sidebar__tabs-item--active"
        );
        this.uaLotsChange.addClass("filter-sidebar__tabs-item--active");
        $(".filter-sidebar__content--usa").hide();
        $(".filter-sidebar__content--ua").show();
        ua.getFilters(this.searchQuery);
        ua.getResults(this.searchQuery).done((data, textStatus, request) => {
          const obj = JSON.stringify(data);
          const results = $.parseJSON(obj);
          if (this.priceRange && !this.priceRangeUAInit) {
            this.sliderUAInit(results);
          }
          this.priceRangeUAInit = true;
        });
      } else {
        $(".filter-sidebar__tabs-item").removeClass(
          "filter-sidebar__tabs-item--active"
        );
        this.usaLotsChange.addClass("filter-sidebar__tabs-item--active");
        $(".filter-sidebar__content--usa").show();
        $(".filter-sidebar__content--ua").hide();
        usa.getFilters(this.searchQuery).done((data, textStatus, request) => {
          const obj = JSON.stringify(data);
          const results = $.parseJSON(obj);
          this.usaDictionary = results;
          if (this.priceRangeUSA && !this.priceRangeUSAInit) {
            this.sliderUSAInit(results);
          }
          this.requestSearch();
          this.priceRangeUSAInit = true;
        });
      }
    });

    // ONCLICK USA LOTS

    this.usaLotsChange.on("click", () => {
      this.searchQuery = Object.assign({}, searchQueryInit);
      this.searchQuery.lotsCountry = "usa";
      usa.getFilters(this.searchQuery).done((data, textStatus, request) => {
        const obj = JSON.stringify(data);
        const results = $.parseJSON(obj);
        this.usaDictionary = results;
        if (!this.priceRangeUSAInit) {
          this.sliderUSAInit(results);
        }
        this.requestSearch();
        this.priceRangeUSAInit = true;
      });

      $(".lot-filter__main")[0].scrollIntoView();
      // $(".your-filters .your-filters__cancel").click(); //ВАЖНО: создает двойной запрос
    });

    // lots page tabs from ANIM TO DO REFACTOR
    $(".filter-sidebar__tabs-item").on("click", function() {
      $(".filter-sidebar__tabs-item").removeClass(
        "filter-sidebar__tabs-item--active"
      );
      if ($(this).attr("data-id") === "tabUSA") {
          $("[data-id='tabUSA']").addClass("filter-sidebar__tabs-item--active");
          $(".filter-sidebar__content--usa").show();
          $(".filter-sidebar__content--ua").hide();
      } else if ($(this).attr("data-id") === "tabUA") {
          $("[data-id='tabUA']").addClass("filter-sidebar__tabs-item--active");
          $(".filter-sidebar__content--ua").show();
          $(".filter-sidebar__content--usa").hide();
      }
    });

    // ONCLICK UA LOTS

    this.uaLotsChange.on("click", () => {
      this.searchQuery = Object.assign({}, searchQueryInit);
      this.searchQuery.lotsCountry = "ua";
      ua.getResults(this.searchQuery).done((data, textStatus, request) => {
        const obj = JSON.stringify(data);
        const results = $.parseJSON(obj);
        if (this.priceRange && !this.priceRangeUAInit) {
          this.sliderUAInit(results);
        }
        this.priceRangeUAInit = true;
      });
      this.requestFilter();
      $(".lot-filter__main")[0].scrollIntoView();
    });

    // PAGINATION EVENTS
    this.paginationContiner.on("click", ".pagination__btn--prev", () => {
      this.searchQuery.currentPage--;
      this.requestSearch();
      $(".lot-filter__main")[0].scrollIntoView();
    });
    this.paginationContiner.on("click", ".pagination__btn--next", () => {
      this.searchQuery.currentPage++;
      this.requestSearch();
      $(".lot-filter__main")[0].scrollIntoView();
    });
    this.paginationContiner.on("click", ".pagination__btn--page", event => {
      this.searchQuery.currentPage = $(event.target).attr("data-page");

      this.requestSearch();
      $(".lot-filter__main")[0].scrollIntoView();
    });

    // desktop sort on lots page
    $(".lot-filter__sort-current").on("click", function() {
      let el = $(this)
        .parent()
        .find(".lot-filter__sort-items");
      $(".lot-filter__sort-items")
        .not(el)
        .removeClass("lot-filter__sort-items--open");
      el.toggleClass("lot-filter__sort-items--open");
    });

    // select item in desktop sort menu
    $(".lot-filter__sort-item").on("click", e => {
      let $this = $(e.currentTarget);
      let value = $this.text();
      this.searchQuery.order = $this.data("order");
      this.searchQuery.orderby = $this.data("orderby");
      this.requestSearch();
      let thisSelectBox = $this.parent().parent();
      $this.addClass("lot-filter__sort-item--active");
      thisSelectBox.find(".lot-filter__sort-current").text(value);
      thisSelectBox
        .find(".lot-filter__sort-items")
        .removeClass("lot-filter__sort-items--open");
    });

    //mobile sorts on lots page, click calling click
    $(".mobile-select__sort-item").on("click", function (e) {
      e.preventDefault();
      $(`.lot-filter__sort-item[data-orderby=${this.dataset.orderby}][data-order=${this.dataset.order}]`).click();
      $("#sortMenu").click();
    });
    $("#mobile-select--cancel").on("click", function (e) {
      e.preventDefault();
      $(".your-filters .your-filters__cancel").click();
    });
    $(".mobile-select__btns .brand-btn").on("click", function (e) {
      e.preventDefault();
      const filterMenu = $("#filterMenu");
      filterMenu.click();
      $("html, body").animate({ scrollTop: filterMenu.offset().top + filterMenu.outerHeight(true) }, 500);
    });
    // open double selects
    // this.filterContainer.on("click", ".double-select__current", function() {
    //   console.log("ho");
    //   let el = $(this)
    //     .parent()
    //     .find(".default-select__items");
    //   el.not($(this).next()).removeClass("default-select__items--open");
    //   $(this)
    //     .next()
    //     .toggleClass("default-select__items--open");
    // });

    // select item in default-select
    this.filterContainer.on("click", ".default-select__item", e => {
      let value = $(e.currentTarget).text();
      let thisSelectBox = $(e.currentTarget)
        .parent()
        .parent();
      $(e.currentTarget)
        .parent()
        .children()
        .removeClass("default-select__item--active");
      $(e.currentTarget).addClass("default-select__item--active");
      let queryParam = $(e.currentTarget)
        .parent()
        .data("select");

      this.searchQuery[queryParam] = $(e.currentTarget).data("value");
      if (queryParam == "brand") {
        this.searchQuery["model"] = "all";
        this.searchQuery.currentPage = 1;
        $(".default-select__items[data-select='model']").siblings(".default-select__current").removeClass("default-select__current--active").text("Модель Вашего авто");
      }

      this.requestFilter();

      this.requestSearch();
      if (!thisSelectBox.hasClass("double-select")) {
        thisSelectBox
          .find(".default-select__current")
          .text(value)
          .addClass("default-select__current--active");
      } else {
        $(e.currentTarget)
          .parent()
          .prev()
          .text(value)
          .addClass("default-select__current--active");
      }
      thisSelectBox
        .find(".default-select__items")
        .removeClass("default-select__items--open");
    });

    this.filterCheckBoxVal("body_style");
    this.filterCheckBoxVal("engine");
    this.filterCheckBoxVal("transmission");
    this.filterCheckBoxVal("fluel");
    this.filterCheckBoxVal("drive_unit");
    this.filterCheckBoxVal("auction_type");
    this.filterCheckBoxVal("location");
    this.filterCheckBoxVal("damage");

    this.filterContainer.on("click", ".double-select__current", function() {
      let el = $(this)
        .parent()
        .find(".default-select__items");
      el.not($(this).next()).removeClass("default-select__items--open");
      $(this)
        .next()
        .toggleClass("default-select__items--open");
    });

    // ON INPUT
    $("#body_style_input, #engine_input, #location_input").on(
      "keyup",
      function() {
        var value = $(this)
          .val()
          .toLowerCase();
        $(this)
          .next()
          .children()
          .filter(function() {
            $(this).toggle(
              $(this)
                .text()
                .toLowerCase()
                .indexOf(value) > -1
            );
          });
      }
    );

    // ONCLICK ABORT FILTERS

    $(".your-filters").on("click", ".your-filters__cancel", () => {
      this.searchQuery.currentPage = 1;
      this.searchQuery.lotsCountry === "ua"
        ? (this.searchQuery = Object.assign({}, searchQueryInit))
        : (this.searchQuery = Object.assign({}, searchQueryInit, {
            lotsCountry: "usa",
          }));
      this.requestSearch();
      this.requestFilter();

      this.resetFilters();
    });

    $(".your-filters").on("click", ".your-filters__item-remove", e => {
      this.searchQuery.currentPage = 1;
      const $this = $(e.currentTarget);
      const param = $this.data("filter");

      if (param == "price_min_max") {
        const side = $this.data("filter-side");
        if (side == "min") {
          this.searchQuery[param][0] = 0;
        } else if (side == "max"){
          if (this.priceRange.noUiSlider) {
            this.searchQuery[param][1] = this.priceRange.noUiSlider.options.range.max;
          }
          if (this.priceRangeUSA.noUiSlider) {
            this.searchQuery[param][1] = this.priceRangeUSA.noUiSlider.options.range.max;
          }
        } else {
          this.searchQuery[param] = searchQueryInit[param];
        }
        this.requestSearch();
        this.requestFilter();
        this.resetFilter(param,side);
      } else {
        this.searchQuery[param] = searchQueryInit[param];
        this.requestSearch();
        this.requestFilter();
        this.resetFilter(param);
      }


    });
  }

  //Functions
  resetFilters() {
    $(".your-filters__content .your-filters__item").remove();
    $(".filter-sidebar .default-checkbox__check").prop("checked", false);
    $(
      ".filter-sidebar .default-select__current:not(.default-select__current--currency)"
    ).each(function() {
      $(this)
        .text($(this).data("placeholder"))
        .removeClass("default-select__current--active");
    });
    if (this.priceRange.noUiSlider) {
      this.priceRange.noUiSlider.reset();
    }
    if (this.priceRangeUSA.noUiSlider) {
      this.priceRangeUSA.noUiSlider.reset();
    }
  }

  resetFilter(dataName,side) {
    if (dataName == "price_min_max") {
      let valueSlider = [null, null];
      if (side == "min") {
        valueSlider[0] = 0;
      } else if (side == "max"){
        if (this.priceRange.noUiSlider) {
          valueSlider[1] = this.priceRange.noUiSlider.options.range.max;
        }
        if (this.priceRangeUSA.noUiSlider) {
          valueSlider[1] = this.priceRangeUSA.noUiSlider.options.range.max;
        }
      }
      if (this.priceRange.noUiSlider) {
        this.priceRange.noUiSlider.set(valueSlider);
      }
      if (this.priceRangeUSA.noUiSlider) {
        this.priceRangeUSA.noUiSlider.set(valueSlider);
      }
      $(`.your-filters__item-remove[data-filter-side=${side}]`).parent().remove();
      setTimeout(()=>{
        $(`.your-filters__item-remove[data-filter-side=${side}]`).parent().remove();
      },100);
      return;
    }

      let items = $("[data-select='" + dataName + "']");
      // items.children().remove();
      items
          .prev()
          .text(items.prev().data("placeholder"))
          .removeClass("default-select__current--active");

  }

  addSearchHTML() {
    $(".lot-filter__main").append(`
    <div class="lot-filter__main-header">
    <span class="lot-filter__result">
      По вашему запросу найдено
      <strong></strong>
      результат(-ов)
    </span>
    <div class="lot-filter__sort">
      <span class="lot-filter__sort-desc">Сортировать как:</span>
      <div class="lot-filter__sort-current">Сначала новые</div>
      <div class="lot-filter__sort-items">
        <div class="lot-filter__sort-item" data-order="asc" data-orderby="lot_ua_price">Сначала дешевые</div>
        <div class="lot-filter__sort-item" data-order="desc" data-orderby="lot_ua_price">Сначала дорогие</div>
        <div class="lot-filter__sort-item" data-order="desc" data-orderby="lot_ua_year">Сначала новые</div>
        <div class="lot-filter__sort-item" data-order="asc" data-orderby="lot_ua_year">Сначала старые</div>
      </div>
    </div>
  </div>
  <div class="lot-filter__main-content">

  </div>
  <div class="pagination">

  </div>
    `);
  }

  defineCountryFilterClass() {
    return ".filter-sidebar__content--" + this.searchQuery.lotsCountry;
  }

  filterCheckBoxVal(param) {
    $("[data-select='" + param + "']").on("change", "input", e => {
      let targetEl = $(
        ".filter-sidebar__content--" +
          this.searchQuery.lotsCountry +
          " [data-select='" +
          param +
          "'] .default-checkbox__check:checkbox:checked"
      )
        .map(function() {
          return $(this).val();
        })
        .get();
      this.searchQuery[param] = targetEl;
      this.requestSearch();
    });
  }
  requestSearch() {
    this.searchQuery.lotsCountry === "ua"
      ? ua.getResults(this.searchQuery)
      : usa.getResults(this.searchQuery, this.usaDictionary);
    this.makeUrlData();
  }
  requestFilter() {
    this.searchQuery.lotsCountry === "ua"
      ? ua.getFilters(this.searchQuery)
      : usa.getFilters(this.searchQuery);
    this.makeUrlData();
  }
  makeUrlData (change_url) {
    const forwardUrl = new URL(window.location.href); //split("?")[0]
    // console.log(this.searchQuery);
    Object.keys(this.searchQuery).forEach(key => {
      if (this.searchQuery[key] !== 0 && this.searchQuery[key].length !== 0) {
        forwardUrl.searchParams.set(key, JSON.stringify(this.searchQuery[key]));

        let item;
        if (this.searchQuery.lotsCountry === "ua") {
          item = $(".filter-sidebar__content--ua [data-select='"+key+"']");
        } else {
          item = $(".filter-sidebar__content--usa [data-select='"+key+"']");
        }

        if (item.hasClass("default-select__items")) {
          let text = item.find("[data-value='"+this.searchQuery[key]+"']").text();

          if (text != 0) {
            item.prev(".default-select__current").addClass("default-select__current--active").text(text);
          }
        } else if (item.hasClass("filter-car__items")) {
          item.closest(".filter-car__details").addClass("filter-car__details--open");

          new Object(this.searchQuery[key]).forEach( (k) => {
            item.find("input[value='"+k+"']").prop("checked", true);
          });
        }
      } else {
        forwardUrl.searchParams.delete(key);
      }
    });
    if (change_url) {
        window.history.replaceState({}, document.title, encodeURI(forwardUrl)+"#");
        window.location.hash = 'filter';
    }
  }
  linkFilter() {
    const url = decodeURI(window.location.href);
    const urlObj = new URL(url);
    if (url.indexOf("?") > -1) {
      Object.keys(this.searchQuery).forEach(key => {
        let v = urlObj.searchParams.get(key);
        if (v) {
            this.searchQuery[key] = JSON.parse(v);
        }
      });
    }
    window.history.replaceState({}, document.title, window.location.pathname+"#");
    window.location.hash = 'filter';
  }

  sliderUAInit(results) {
    NoUISlider.create(this.priceRange, {
      start: [results.lotsUAMinPrice, results.lotsUAMaxPrice],
      step: 500,
      connect: true,
      range: {
        min: results.lotsUAMinPrice,
        max: results.lotsUAMaxPrice,
      },
    });
    let minValue = $(
      ".filter-sidebar__content--ua .price-diagram__value-box--min"
    );
    let maxValue = $(
      ".filter-sidebar__content--ua .price-diagram__value-box--max"
    );
    this.priceRange.noUiSlider.on("update", function(values, handle) {
      if (handle) {
        maxValue.text("$" + Math.round(parseInt(values[handle])));
      } else {
        minValue.text("$" + Math.round(parseInt(values[handle])));
      }
    });
    this.priceRange.noUiSlider.on("change", () => {
      this.searchQuery.price_min_max = this.priceRange.noUiSlider
        .get()
        .map(n => parseInt(n));
      ua.getResults(this.searchQuery).done((data, textStatus, request) => {
        this.priceRange.noUiSlider.updateOptions({
          range: {
            min: data.lotsUAMinPrice,
            max: data.lotsUAMaxPrice,
          },
        });
      });
    });
  }

  sliderUSAInit(results) {
    NoUISlider.create(this.priceRangeUSA, {
      start: [+results.price.min, +results.price.max],
      step: 500,
      connect: true,
      range: {
        min: +results.price.min,
        max: +results.price.max,
      },
    });
    let minValue = $(
      ".filter-sidebar__content--usa .price-diagram__value-box--min"
    );
    let maxValue = $(
      ".filter-sidebar__content--usa .price-diagram__value-box--max"
    );
    this.priceRangeUSA.noUiSlider.on("update", function(values, handle) {
      if (handle) {
        maxValue.text("$" + Math.round(parseInt(values[handle])));
      } else {
        minValue.text("$" + Math.round(parseInt(values[handle])));
      }
    });
    this.priceRangeUSA.noUiSlider.on("change", () => {
      this.searchQuery.price_min_max = this.priceRangeUSA.noUiSlider
        .get()
        .map(n => parseInt(n));
      usa
        .getResults(this.searchQuery, this.usaDictionary)
        .done((data, textStatus, request) => {
          this.priceRangeUSA.noUiSlider.updateOptions({
            range: {
              min: +results.price.min,
              max: +results.price.max,
            },
          });
          usa.getFilters(this.searchQuery);
        });
    });
  }
}

export default Filter;
