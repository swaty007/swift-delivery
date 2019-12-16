class Modal {
    constructor() {
        this.events();

    }
    events() {
        this.spinner();
        $(document).on('click', '.modal__close', e => {
            let $this = $(e.currentTarget);
            $this.closest(".modal").hide('fade', 300);
        });
    }
    spinner () {
        $(".spinner input").spinner({
            min: 1,
            max: 50,
            start: 1,
            change: function( e ) {
                if (isNaN(this.value)) {
                    this.value = 1;
                }
                $('label[for='+  this.id  +']').text("$"+(this.value*this.dataset.price).toFixed(2));
            },
            spin: function( e, ui ) {
                $('label[for='+  this.id  +']').text("$"+(ui.value*this.dataset.price).toFixed(2));
            },
        });
    }
}

export default Modal;