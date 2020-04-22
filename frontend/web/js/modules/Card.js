import {finishPjax} from "./global_functions";

class Card {
    constructor() {
        this.events();

    }

    events() {
        $("#form-create-order").on('submit', e => {
            let emptyCardBlock = $("#order_card");
            let cardTotalBlock = $("#order_card_total");
            let cardLowerModal = $("#card_lower_100");
            if (emptyCardBlock.length !== 0) {
                e.preventDefault();
                emptyCardBlock.addClass('card__item--error');
            } else if (cardTotalBlock.attr('data-total') < 100) {
                e.preventDefault();
                cardLowerModal.show('fade', 300);
            }
        });
        //add to card click
        $(document).on("click", "#add_to_card_btn", e => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                modalEl = $this.closest('.modal'),
                optionEl = $("#modal_card_select :selected"),
                spinnerEl = $("#item_quanitity"),
                data = {
                    option: optionEl.attr('data-id'),
                    count: spinnerEl.val(),
                    // product_id: optionEl.attr('data-product_id'),
                };
            console.log(data);
            $.ajax({
                type: "POST",
                url: "/api/add-to-cart",
                cache : false,
                data: data,
                success: function (msg) {
                    console.log(msg);
                    if (msg.result) {
                        // $("#zip_validate").closest('.form-group').removeClass('has-error').addClass('has-success');
                        // $("#zip_validate_status").text('You write available zip code');
                    } else {
                        // $("#zip_validate").closest('.form-group').removeClass('has-success').addClass('has-error');
                        // $("#zip_validate_status").text('You write unavailable zip code');
                    }
                    finishPjax("#card_pjax");
                    finishPjax("#card_pjax_info");
                }
            });
            modalEl.addClass("modal--success");
            setTimeout(() => {
                modalEl.hide('fade', 400, () => {
                    // $("body").removeClass("modal__open");
                    modalEl.removeClass("modal--success");
                });
            }, 600);
        });


        //remove from cart
        $(document).on("click", ".order .card__delete", e => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                data = {
                    option: $this.attr('data-id'),
                    full: true
                    // product_id: optionEl.attr('data-product_id'),
                };
            console.log(data);
            $.ajax({
                type: "POST",
                url: "/api/remove-from-cart",
                cache : false,
                data: data,
                success: function (msg) {
                    console.log(msg);
                    if (msg.result) {
                        // $("#zip_validate").closest('.form-group').removeClass('has-error').addClass('has-success');
                        // $("#zip_validate_status").text('You write available zip code');
                    } else {
                        // $("#zip_validate").closest('.form-group').removeClass('has-success').addClass('has-error');
                        // $("#zip_validate_status").text('You write unavailable zip code');
                    }
                    finishPjax("#card_pjax");
                    finishPjax("#card_pjax_info");
                }
            });
        });
    }


}

export default Card;