import {finishPjax} from "./global_functions";

class Supplier {
    constructor() {
        this.events();

    }

    events() {
        //accept order modal click btn
        $(document).on("click", "#take_order_btn", e => {
            e.preventDefault();
            let $this = $(e.currentTarget),
                modalEl = $this.closest('.modal'),
                inputId = $('#modal_take_order_id'),
                // inputTimeSelect = $('#modal_take_order_time'),
                inputTimeVal = $('#modal_take_order_time_val'),
                orderName = $("#modal_take_order_name"),
                data = {
                    id: inputId.val(),
                    deliverName: orderName.val(),
                    deliveryTime: Number(inputTimeVal.val()) ,
                    // deliveryTime: inputTimeSelect.val() === 'min' ? Number(inputTimeVal.val()) : Number(inputTimeVal.val()) * 60,
                };

            if (data.deliveryTime === 0) {
                inputTimeVal.closest('.form-group').addClass('has-error')
                return
            } else {
                inputTimeVal.closest('.form-group').removeClass('has-error')
            }
            if (data.deliverName === "") {
                // orderName.closest('.form-group').addClass('has-error')
                // return
                data.deliverName = 'David'
            } else {
                // orderName.closest('.form-group').removeClass('has-error')
            }
            console.log(data);
            $.ajax({
                type: "POST",
                url: "/supplier/take-order",
                cache : false,
                data: data,
                success: function (msg) {
                    console.log(msg);
                    orderName.val('');
                    // inputTimeSelect.val('');
                    inputTimeVal.val('');
                    if (msg.result) {
                        // $("#zip_validate").closest('.form-group').removeClass('has-error').addClass('has-success');
                        // $("#zip_validate_status").text('You write available zip code');
                    } else {
                        // $("#zip_validate").closest('.form-group').removeClass('has-success').addClass('has-error');
                        // $("#zip_validate_status").text('You write unavailable zip code');
                    }
                    finishPjax("#supplier_tables");
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

        $(document).on('click', '[data-direction=show-more-orders]', e => {
            e.preventDefault();
            let $this = $(e.currentTarget);

            // $this.closest('tr').next().is(':hidden').siblings('tr').slideUp(300);
            $this.closest('tr').next().find('.supplier-cab__table-content').slideToggle(300);
        });
    }


}

export default Supplier;