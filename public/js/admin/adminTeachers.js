function blockOrUnblock(id) {
    let access = $(`#current-value-${ id }`).val();
    let requestData = {};
    switch (access) {
        case 'block':
            requestData.blocked = true;
            break;
        case 'unblock':
            requestData.blocked = false;
            break;
        default:
            toastr.error('Bad request');
    }

    $.ajax({
        url: `${ location.pathname }/${ access }/${ id }`,
        type: 'PUT',
        data: JSON.stringify(requestData),
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        success: function (response) {
            switch (access) {
                case 'block':
                    toastr.success(`Успешно блокирахте ${ response.data.name }`);
                    break;
                case 'unblock':
                    toastr.success(`Успешно отблокирахте ${ response.data.name }`);
                    break;
            }
            _changeButton(id, access);
        },
        error: function (response) {
            let err = eval("(" + response.responseText + ")");
            toastr.error(`Error message: ${ err.message } </br> Status code: ${ response.status }`);
        }
    });
}

function _changeButton(id, access) {
    let currentBtn = $(`#current-value-${id}`);
    let accessStatusIcon = $(`#access-status-${id}`);
    let teacherEmail = $(`#email-${id}`);
    switch (access) {
        case "block":
            currentBtn.val('unblock');
            currentBtn.text('ОТБЛОКИРАЙ');
            currentBtn.removeClass('btn-outline-danger');
            currentBtn.addClass('btn-outline-success');
            accessStatusIcon.removeClass('fa-check-circle');
            accessStatusIcon.addClass('fa-times-circle');
            teacherEmail.removeClass('unblocked-email');
            teacherEmail.addClass('blocked-email');
            break;
        case "unblock":
            currentBtn.val('block');
            currentBtn.text('БЛОКИРАЙ');
            currentBtn.removeClass('btn-outline-success');
            currentBtn.addClass('btn-outline-danger');
            accessStatusIcon.removeClass('fa-times-circle');
            accessStatusIcon.addClass('fa-check-circle');
            teacherEmail.removeClass('blocked-email');
            teacherEmail.addClass('unblocked-email');
            break;
    }
}

