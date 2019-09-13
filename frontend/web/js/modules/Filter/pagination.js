import $ from "jquery";

function paginationOutput(pages, currentPage) {
  const paginationContiner = $(".pagination");
  const paginationArray = pagination(currentPage, pages);
  const paginationParsedArray = paginationArray.reduce((acc, p) => {
    if (p !== "...") {
      if (p == pages && !(currentPage > pages - 2)) {
        acc.push(
          `<div class="pagination__btn pagination__btn--more-before ${
            currentPage == p
              ? "pagination__btn--page pagination__btn--active "
              : "pagination__btn--page"
          }" data-page="${p}">${p}</div>`
        );
      } else {
        if (currentPage > ($(window).width() <= 640 ? 4:5) && p == 1) {
          acc.push(
            `<div class="pagination__btn pagination__btn--more-after ${
              currentPage == p
                ? "pagination__btn--page pagination__btn--active "
                : "pagination__btn--page"
            }" data-page="${p}">${p}</div>`
          );
        } else {
          acc.push(
            `<div class="pagination__btn ${
              currentPage == p
                ? "pagination__btn--page pagination__btn--active"
                : "pagination__btn--page"
            }" data-page="${p}">${p}</div>`
          );
        }
      }
    }
    return acc;
  }, []);
  let htmlPagination = paginationParsedArray.join("");
  paginationContiner.html(`
        ${
          currentPage != 1
            ? '<div class="pagination__btn pagination__btn--prev">Предыдущая</div>'
            : ""
        }
    ${htmlPagination}
        ${
          pages != currentPage
            ? '<div class="pagination__btn pagination__btn--next">Следующая</div>'
            : ""
        }
    `);
}

function pagination(c, m) {
  var current = +c,
    last = +m,
    delta = $(window).width() <= 640 ? 1 : 2,
    left = current - delta,
    right = current + delta + 1,
    range = [],
    rangeWithDots = [],
    l;

  for (let i = 1; i <= last; i++) {
    if (i == 1 || i == last || (i >= left && i < right)) {
      range.push(i);
    }
  }

  for (let i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push("...");
      }
    }
    rangeWithDots.push(i);
    l = i;
  }
  return rangeWithDots;
}

export { paginationOutput };
