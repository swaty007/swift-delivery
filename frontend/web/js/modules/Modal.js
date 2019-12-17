class Modal {
    constructor() {
        this.events();

    }

    events() {
        this.spinner();

        //close modal
        $(document).on('click', '.modal__close', e => {
            let $this = $(e.currentTarget);
            $this.closest(".modal").hide('fade', 300);
        });

        //select element change
        $(document).on('change', '#modal_card_select', e => {
            let $this = $(e.currentTarget),
                price = $this.find(":selected").attr("data-price");
            $("#item_quanitity").attr("data-price", price);
            $('label[for=item_quanitity]').text("$" + ($('#item_quanitity').val() * price).toFixed(2));
        });

        //add to card click
        $(document).on("click", "#add_to_card_btn", e => {
            let $this = $(e.currentTarget),
                modalEl = $this.closest('.modal'),
                optionEl = $("#modal_card_select :selected"),
                spinnerEl = $("#item_quanitity"),
                data = {
                    count: spinnerEl.val(),
                    id: optionEl.attr('data-id'),
                    product_id: optionEl.attr('data-product_id')
                };
            console.log(data);

        });

        //item list button click and open modal with data
        $(document).on('click', '.item .item__button', e => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                itemEl = $this.closest(".item"),
                modalEl = $("#add_to_card"),
                data = [],
                inputData = $this.siblings(".item__data");

            inputData.each((i, el) => {
                data.push($(el).data());
            });
            data.sort((a, b) => {
                if (a.order < b.order) {
                    return -1;
                } else if (a.order > b.order) {
                    return 1;
                }
                return 0;
            });

            modalEl.find('.modal__title').text(itemEl.find(".item__title").text());
            modalEl.find('.modal__img').attr("src", itemEl.find(".item__img").attr("src"));
            $("#item_quanitity").val(1);
            $("#item_quanitity").attr("data-price", data[0].price);
            $('label[for=item_quanitity]').text("$" + Number(data[0].price).toFixed(2));

            $("#modal_card_select").find("option").remove();
            data.forEach((val, index) => {
                $("#modal_card_select").append(`<option data-price="${val.price}" data-id="${val.id}" data-product_id="${val.product_id}" value="${val.id}">${val.name} $${val.price}</option>`);
            });
            modalEl.show('fade', 300);
        });
    }

    spinner() {
        $(".spinner input").spinner({
            min: 1,
            max: 50,
            start: 1,
            change: function (e) {
                if (isNaN(this.value)) {
                    this.value = 1;
                }
                $('label[for=' + this.id + ']').text("$" + (this.value * this.dataset.price).toFixed(2));
            },
            spin: function (e, ui) {
                $('label[for=' + this.id + ']').text("$" + (ui.value * this.dataset.price).toFixed(2));
            },
        });
    }
}

export default Modal;