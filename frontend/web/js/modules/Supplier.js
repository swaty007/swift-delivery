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
                optionEl = $("#modal_take_order_select :selected"),
                orderIdEl = $("#modal_take_order_id"),
                data = {
                    id: optionEl.attr('data-id'),
                    count: orderIdEl.val(),
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
                    finishPjax("#supplier_tables");
                }
            });
            modalEl.addClass("modal--success");
            setTimeout(() => {
                modalEl.hide('fade', 400, () => {
                    $("body").removeClass("modal__open");
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