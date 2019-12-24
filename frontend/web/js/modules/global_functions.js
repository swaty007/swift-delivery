export function finishPjax(el) {
    if(typeof $.pjax !== 'undefined') {
        if (el !== undefined) {
            $.pjax.reload({container: el, async: false});
        } else {
            $.pjax.reload({container: '#p0', async: false});
        }
    }
}

export function showToastr(msg) {
    if (msg.msg === 'ok') {
        toastr['success'](msg.status, '');
    } else if(msg.msg === 'error'){
        toastr['error'](msg.status, '');
    }
}