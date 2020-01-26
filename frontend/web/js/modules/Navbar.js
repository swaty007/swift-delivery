class Navbar {
    constructor() {
        this.events();
    }
    events() {
        $( document ).ready(function() {
            var block = 0;
            $(document).on('click', '#menu_btn', function () {
                if (block === 1) {return;}
                block = 1;
                $(this).toggleClass('collapsed');
                $('body').toggleClass('show-scc-navbar');
//                    $('#scc_collapse').slideToggle();
                setTimeout(function () {
                    $('#navbar_collapse').show();
                    block = 0;
                },300);
            });
        });
    }

}

export default Navbar;