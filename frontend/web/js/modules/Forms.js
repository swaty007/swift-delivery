import $ from "jquery";

class Forms {
  constructor(){
    this.formContainer = $(".trade-form--buy-tire");
    this.events();
  }

  events() {
    this.formContainer.on("click", ".default-select__item", e => {
      let $this = $(e.currentTarget);
      let value = $this.text();
      let thisSelectBox = $this.parent().parent();
      $this
        .parent()
        .children()
        .removeClass("default-select__item--active");
      $this.addClass("default-select__item--active");
      if (!thisSelectBox.hasClass("double-select")) {
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
}

export default Forms;