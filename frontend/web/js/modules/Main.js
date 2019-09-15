class Main {
    constructor() {
        this.events();
    }
    events() {
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
}

export default Main;