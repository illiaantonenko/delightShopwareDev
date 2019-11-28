// When the user clicks anywhere outside of the modal, close it
// window.onclick = function (event) {
//     if (event.target == callbackModal) {
//         callbackModal.style.display = "none";
//     }
// }

function showCallbackModel() {
    $('#callback-modal').show();
    window.addEventListener('keyup', escapeCallback);
}

function hideCallbackModal() {
    $('#callback-modal').hide();
    $('#message').hide();
    window.addEventListener('keyup', escapeCallback);

}

function sendCallbackForm(e) {
    e.preventDefault();
    let form = $('#callback-form'),
        message = $('#message');
    if (validateForm(form)) {
        $.ajax({
            type: 'post',
            url: '/shopware/delight-frontend-form',
            dataType: 'html',
            data: form.serialize(),
            success: function (response) {
                let responseObj = JSON.parse(response)[0];
                message.find('.modal-header')[0].innerText = "Success";
                message.find('.modal-content')[0].innerText = responseObj["message"];
                message.show();
            },
            error: function (response) {
                let responseObj = JSON.parse(response)[0];
                message.find('.modal-header')[0].innerText = "Error";
                message.find('.modal-content')[0].innerText = "Error " + responseObj["status"] + " " + responseObj["message"];
                message.show();
            }
        });
    }
}

function validateForm(form) {
    let inputs = form.find('.field'),
        errors = form.find('.error'),
        flag = true;

    try {
        errors.remove();
        flag = true;
    } catch (e) {
        console.log(e);
    }

    for (let i = 0; i < inputs.length; i++) {
        if (!inputs[i].value) {
            inputs[i].after(createError('Cannot be blank.'));
            flag = false;
        } else {
            if (inputs[i].name === 'phone') {
                if (!inputs[i].value.match(/^\+?\d[\d() -]{4,14}\d$/)) {
                    inputs[i].after(createError('Invalid phone number.'));
                    flag = false;
                }
            }
        }

    }
    return flag;

}

function createError(text) {
    let error = document.createElement('div');
    error.className = 'error';
    error.style.color = 'red';
    error.innerHTML = text;
    return error;
}

function escapeCallback(event) {
    if (event.code === 'Escape') {
        hideCallbackModal();
    }
}