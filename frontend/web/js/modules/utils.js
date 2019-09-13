function textTruncate(str, length, ending) {
  if (!str) {
    return "";
  }
  if (length == null) {
    length = 12;
  }
  if (ending == null) {
    ending = "...";
  }
  if (str.length > length) {
    return str.substring(0, length - ending.length) + ending;
  } else {
    return str;
  }
}

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(
      (amount = Math.abs(Number(amount) || 0).toFixed(decimalCount))
    ).toString();
    let j = i.length > 3 ? i.length % 3 : 0;

    return (
      negativeSign +
      (j ? i.substr(0, j) + thousands : "") +
      i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
      (decimalCount
        ? decimal +
          Math.abs(amount - i)
            .toFixed(decimalCount)
            .slice(2)
        : "")
    );
  } catch (e) {
    console.log(e);
  }
}

function checkboxGenerate(target, obj) {
  target.html(`
    ${(Array.isArray(obj) ? obj : Object.keys(obj))
      .map(
        item => `
          <label for="${item}" class="default-checkbox">
          <input type="checkbox" id="${item}" value="${item}" class="default-checkbox__check">
          <span class="default-checkbox__title">${
            Array.isArray(obj) ? item : obj[item]
          }</span>
        </label>
        `
      )
      .join("")}`);
}

function between(x, min, max) {
  return x >= min && x <= max;
}

function hasProperty(object, key) {
  return object ? hasOwnProperty.call(object, key) : false;
}

export { textTruncate, formatMoney, checkboxGenerate, between, hasProperty };
