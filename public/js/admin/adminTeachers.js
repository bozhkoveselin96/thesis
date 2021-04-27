function _changeButton(id, blocked) {
    let currentBtn = $(`#current-value-${id}`);
    let accessStatusIcon = $(`#access-status-${id}`);
    let teacherEmail = $(`#email-${id}`);
    switch (blocked) {
        case "block":
            currentBtn.val('unblock');
            currentBtn.text('UNBLOCK');
            currentBtn.removeClass('btn-outline-danger');
            currentBtn.addClass('btn-outline-success');
            accessStatusIcon.removeClass('fa-check-circle');
            accessStatusIcon.addClass('fa-times-circle');
            teacherEmail.removeClass('approved-email');
            teacherEmail.addClass('blocked-email');
            break;
        case "unblock":
            currentBtn.val('block');
            currentBtn.text('BLOCK');
            currentBtn.removeClass('btn-outline-success');
            currentBtn.addClass('btn-outline-danger');
            accessStatusIcon.removeClass('fa-times-circle');
            accessStatusIcon.addClass('fa-check-circle');
            teacherEmail.removeClass('blocked-email');
            teacherEmail.addClass('approved-email');
            break;
    }
}

function blockOrUnblock(id) {
    let route = $(`#current-value-${ id }`).val();
    let requestData = {};
    switch (route) {
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
        url: `${ route }/${ id }`,
        type: 'PUT',
        data: JSON.stringify(requestData),
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        success: function (response) {
            toastr.success(`You ${ route }ed successfully  ${ response.data.name }`);
            _changeButton(id, route);
        },
        error: function (response) {
            let err = eval("(" + response.responseText + ")");
            toastr.error(`Error message: ${ err.message } </br> Status code: ${ response.status }`);
        }
    });
}

