import $ from "jquery";
import SimpleLightbox from "simple-lightbox";
SimpleLightbox.registerAsJqueryPlugin($);

//slider USA shop
let results = [];

function updateData(data) {
    results = data;
}
function getData() {
    return results;
}

$(document).on("click",".photo-card__pic-total", function (e) {
    e.stopPropagation();
    e.preventDefault();
    var object = results.find(x => Number(x.id) === Number(this.dataset.id))['images'];
    if (object[0].preview) {
        object = object.map(x => x.preview);
    }
    // console.log(object,'init slider', results);
    var lightbox = SimpleLightbox.open({
        items: object
    });
});
// end slider USA shop

// slider profitable
function initProfitableSlider() {
    let currIndex = 0,
        totalSlide = $(".profitable__content > .profitable__item").length - 1,
        timerId,
        block = false;

    $(".trade-animation__item").on("click", function() {
        currIndex = $(this).index();
        $(".trade-animation__item").removeClass(
            "trade-animation__item--active"
        );
        $(this).addClass("trade-animation__item--active");
        $(".profitable__item").removeClass("profitable__item--active");
        $(".profitable__item")
            .eq(currIndex)
            .addClass("profitable__item--active");
        setBlock(4000);
    });
    function setBlock(time) {
        block = true;
        clearTimeout(timerId);
        timerId = setTimeout(() => {
            block = false;
        },time);
    }
    function autoslide() {
        if (block) {return;}
        setBlock(2000);
        if (currIndex === totalSlide) {
            currIndex = 0;
        } else {
            currIndex++;
        }
        $(".trade-animation__item").removeClass("trade-animation__item--active");
        $(".trade-animation__item")
            .eq(currIndex)
            .addClass("trade-animation__item--active");
        $(".profitable__item").removeClass("profitable__item--active");
        $(".profitable__item")
            .eq(currIndex)
            .addClass("profitable__item--active");
    }
    setInterval(autoslide,4000);

}
// end slider profitable

export { updateData, getData, initProfitableSlider };
