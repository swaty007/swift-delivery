import {finishPjax} from "./global_functions";

class Modal {
    constructor() {
        this.events();

    }

    events() {
        this.spinnerInit();
        this.ordersInit();
        this.takeOrder();
        this.timer();
        this.autoremove();

        //close modal
        $(document).on('click', '.modal__close', e => {
            let $this = $(e.currentTarget);
            $this.closest(".modal").hide('fade', 300);
            // $("body").removeClass("modal__open");
        });


    }
    timer () {
        setInterval(() => {
            let modal = $('#modal__time'),
              timer = Number(modal.text());
            if (!Number.isNaN(timer)) {
                if (--timer >= 0) {
                    modal.text(timer)
                } else {
                    modal.text(0)
                }

            }
        }, 1000)
    }
    autoremove () {
        // Dom ready
        $(() => {
            $('[data-autoremove]').each(function() {
                setTimeout(() => {
                    $(this).hide()
                }, $(this).attr('data-autoremove'))
            })
        })
    }
    takeOrder () {
        $(document).on('click', '[data-direction=take-order]', e => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                modalEl = $("#take_order"),
                order_id = $this.attr('data-order-id'),
                modalDuration = $('#modal_take_order_duration'),
                modalTime = $('#modal__time'),
                inputId = $('#modal_take_order_id');

            $.ajax({
                type: "GET",
                url: "/supplier/calculate-delivery",
                cache : false,
                data: {
                    id: order_id
                },
                success: function (msg) {
                    console.log(msg);
                    modalDuration.text(msg.duration)
                    modalTime.text(msg.timeToTake)
                    if (msg.result) {
                        // $("#zip_validate").closest('.form-group').removeClass('has-error').addClass('has-success');
                        // $("#zip_validate_status").text('You write available zip code');
                    } else {
                        // $("#zip_validate").closest('.form-group').removeClass('has-success').addClass('has-error');
                        // $("#zip_validate_status").text('You write unavailable zip code');
                    }
                    // finishPjax("#supplier_tables");
                }
            });

            inputId.val(order_id)
            modalEl.show('fade', 300);
            // $("body").addClass("modal__open");
        });
    }
    ordersInit () {
        //select element change
        $(document).on('change', '#modal_card_select', e => {
            let $this = $(e.currentTarget),
                price = $this.find(":selected").attr("data-price");
            $("#item_quanitity").attr("data-price", price);
            $('label[for=item_quanitity]').text("$" + ($('#item_quanitity').val() * price).toFixed(2));
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
            $("#item_note").text($this.attr('data-note'))

            $("#modal_card_select").find("option").remove();
            data.forEach((val, index) => {
                $("#modal_card_select").append(`<option data-price="${val.price}" data-id="${val.id}" data-product_id="${val.product_id}" value="${val.id}">${val.name} $${val.price}</option>`);
            });
            modalEl.show('fade', 300);
            // $("body").addClass("modal__open");
        });
    }
    spinnerInit() {
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