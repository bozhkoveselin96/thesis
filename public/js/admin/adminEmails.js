function openModal(modal, id = null) {
    let currentEmail = (id !== null) ? $(`#email-${ id }`).text() : null;
    switch (modal) {
        case 'create':
        case 'update':
            let submitBtn = $('#update-or-create-btn');
            submitBtn.off('click');
            let title = $('#create-or-update-modal-label');
            if (id !== null) {
                title.text('UPDATE email');
                submitBtn.text('UPDATE');
            } else {
                title.text('Add email');
                submitBtn.text('ADD');
            }
            $('#create-or-update-modal').modal('show');
            $('#modal-input-email').val(currentEmail);
            submitBtn.click(function () {
                let newEmail = $('#modal-input-email').val();
                if (newEmail === currentEmail) {
                    $('#create-or-update-modal').modal('hide');
                    return;
                } else if (newEmail === '') {
                    toastr.warning('Email is required!');
                    return;
                }
                createOrUpdateEmail(id, newEmail, currentEmail, modal);
            });
            break;
        case 'remove':
            $('#remove-modal').modal('show');
            $('#remove-modal-body').text(`Are you sure you want to remove ${ currentEmail } ?`);
            $('#remove').click(function () {
                removeTeacher(id, currentEmail);
            });
            break;
    }
}

function createOrUpdateEmail(id, newEmail, oldEmail, createOrUpdate) {
    let type;
    let route;
    switch (createOrUpdate) {
        case 'create':
            type = 'POST';
            route = location.href.replace('/list', '');
            break;
        case 'update':
            type = 'PUT';
            route = location.href.replace('list', id);
            break;
        default:
            toastr.error('Bad request');
    }

    $.ajax({
        url: route,
        type: type,
        data: JSON.stringify({ email: newEmail }),
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        statusCode: {
            200: function () {
                $('#update-or-create-btn').off('click');
                $(`#email-${ id }`).text(newEmail);
                $('#create-or-update-modal').modal('hide');
                toastr.success(`Successfully update ${ oldEmail } to ${ newEmail }`);
            },
            201: function () {
                location.reload();
            }
        },
        error: function (response) {
            let err = eval("(" + response.responseText + ")");
            let emailError = err.errors.email[0];
            toastr.error(`Message: ${ err.message } </br> Error: ${ emailError } </br> Status code: ${ response.status }`);
        }
    });
}

function removeTeacher(id, email) {
    let route = location.href.replace('list', id);
    $.ajax({
        url: route,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        success: function () {
            location.reload();
        },
        error: function (response) {
            let err = eval("(" + response.responseText + ")");
            toastr.error(`Error message: ${ err.message } </br> Status code: ${ response.status }`);
        }
    });
}

