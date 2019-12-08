class Main {
    constructor() {
        this.events();
    }
    events() {
        this.fileContainer();
        this.spinner();
        $("#zip_validate").on("change", (e) => {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/api/is-zip-allowed",
                // cache : false,
                // processData: false,
                // dataType: 'json',
                // contentType: false,
                data: {
                    zip: $("#zip_validate").val()
                },
                success: function (msg) {
                    console.log(msg);
                    if (msg.result) {
                        // $("#zip_validate").closest('.form-group').removeClass('has-error').addClass('has-success');
                        // $("#zip_validate_status").text('You write available zip code');
                    } else {
                        // $("#zip_validate").closest('.form-group').removeClass('has-success').addClass('has-error');
                        // $("#zip_validate_status").text('You write unavailable zip code');
                    }
                }
            });
        });
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
                    $(input).siblings(".fileContainer__img").attr('src', e.target.result).show();
                    $(input).siblings(".fileContainer__text--select").hide();
                    $(input).siblings(".fileContainer__text--name").addClass('loaded');
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