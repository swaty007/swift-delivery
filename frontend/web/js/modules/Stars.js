class Stars {
    constructor() {
        this.events();
        this.number = -1;
    }
    events() {
        let el = $('#start_select .stars__block');
        el.on('mouseenter', (e) => {
            const hoverIndex = $(e.currentTarget).index()
            el.each((index, el) => {
                if (index <= hoverIndex) {
                    $(el).addClass('stars__block--full')
                } else {
                    $(el).removeClass('stars__block--full')
                }
            })
        })
        el.on('mouseleave', (e) => {
            const hoverIndex = $(e.currentTarget).index()
            el.each((index, el) => {
                if (this.number >= index) {
                    $(el).addClass('stars__block--full')
                } else {
                    $(el).removeClass('stars__block--full')
                }
            })
        })
        el.on('click', (e) => {
            const index = $(e.currentTarget).index();
             this.number = index
            $('#stars_form').val(index + 1)
        });
    }
}

export default Stars;