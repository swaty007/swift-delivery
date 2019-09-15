class Main {
    constructor() {
        this.events();
    }
    events() {
        this.fileContainer();
        this.spinner();
    }
    fileContainer() {
        $(".fileContainer input[type=file]").on('change', function (e) {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                // if (input.files[0].size/1024/1024 > 2) {
                //     toastr['error']("Файл превышает размер в 2мб", '');
                // }
                reader.onload = function(e) {
                    $(".fileContainer .fileContainer__img").attr('src', e.target.result).show();
                    $(".fileContainer .fileContainer__text--select").hide();
                    $(".fileContainer .fileContainer__text--name").addClass('loaded');
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    }
    spinner () {
        $(".spinner input").spinner({
            min: 1,
            max: 50,
            start: 1,
            change: function( event, ui ) {
                $('label[for='+  this.id  +']').text("$"+this.value*this.dataset.price);
            },
            spin: function( event, ui ) {
                $('label[for='+  this.id  +']').text("$"+this.value*this.dataset.price);
            }
        });
    }
}

export default Main;