class Modal {
    constructor() {
        this.events();

    }
    events() {
        this.spinner();
    }
    spinner () {
        $(".spinner input").spinner({
            min: 1,
            max: 50,
            start: 1,
            change: function( event, ui ) {
                $('label[for='+  this.id  +']').text("$"+(ui.value*this.dataset.price).toFixed(2));
            },
            spin: function( event, ui ) {
                $('label[for='+  this.id  +']').text("$"+(ui.value*this.dataset.price).toFixed(2));
            },
            input: function () {
                // $('label[for='+  this.id  +']').text("$"+this.value*this.dataset.price);
            }
        });
    }
}

export default Modal;