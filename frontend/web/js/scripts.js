// 3rd party packages from NPM
// import $ from "jquery";
// import WOW from "wowjs";
// import tocca from "tocca";

// Our modules / classes
// import Anim from "./modules/Anim";
// import HowWeWorkCar from "./modules/HowWeWorkCar";
// import Popups from "./modules/Popups";
// import Validate from "./modules/Validate";
import Main from "./modules/Main";
// import Cursor from "./modules/Cursor";

// Instantiate a new object using our modules/classes
const main = new Main();
// const hwwcar = new HowWeWorkCar();
// const filter = new Filter();
// const popups = new Popups();
// const validate = new Validate();
// const calculator = new Calculator();
// const forms = new Forms();
// const cursor = new Cursor();
function showToastr(msg) {
    if (msg.msg === 'ok') {
        toastr['success'](msg.status, '');
    } else if(msg.msg === 'error'){
        toastr['error'](msg.status, '');
    }
}
function finishPjax(el) {
    if(typeof $.pjax !== 'undefined') {
        if (el !== undefined) {
            $.pjax.reload({container: el});
        } else {
            $.pjax.reload({container: '#p0'});
        }
    }
}
$(document).on('click',"#create_market", function(e) {
    e.preventDefault();
    let data = {
        title: $('#market_name').val(),
        type: $('#market_type').val(),
        description: $('#market_description').val(),
        cost: $('#market_cost').val(),
        time_action: $('#market_time_action').val(),
        count_api: $('#market_count_api').val(),
        file: $("#market_file")[0].files[0]
    };

    let formData = new FormData();

    formData.append("title", data.title);
    formData.append("type", data.type);
    formData.append("description", data.description);
    formData.append("cost", data.cost);
    formData.append("time_action", data.time_action);
    formData.append("count_api", data.count_api);
    formData.append("file", data.file);

    console.log(data);

    $.ajax({
        type: "POST",
        url: "/market/create-market",
        cache : false,
        processData: false,
        dataType: 'json',
        contentType: false,
        data: formData,
        success: function (msg) {
            console.log(msg);
            showToastr(msg);
            finishPjax();
            closeModal($('#market-create'));
        }
    })
});