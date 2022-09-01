$(document).on('click', '#btn-login', function () {
    console.log('cliec');
    var btn = $(this);
    var form = $('#form-login').serialize();
    var url = $('#form-login').attr('action');
    btn.html('<div class="spinner-border text-light" role="status"></div>');
    btn.prop('disabled', true);
    $('#form-login').find('input').prop('disabled', true);

    $.ajax({
        url: url,
        type: 'POST',
        data: form,
        success: (data) => {
            console.log(data);

            window.location.href = data;
        },
        error: (err) => {
            // console.log(err);
            btn.html('ENTRAR');
            btn.prop('disabled', false);
            $('#form-login').find('input').prop('disabled', false);

            Swal.fire({
                icon: 'error',
                title: err.responseJSON.invalid
            });
        }
    });
});


$("#preco").maskMoney({
    decimal: ",",
    thousands: "."
});
$("#extrapreco").maskMoney({
    decimal: ",",
    thousands: "."
});


$(document).on('click', '[data-bs-target="#edit-exame"]', function() {
    $.ajax({
        url: `exame-show/${$(this).data('id')}`,
        // headers: {
        //     'Authorization': 'Bearer ' + localStorage.getItem('session'),
        // },
        type: 'GET',
        success: (data) => {
            console.log(data);
            for (i in data) {
                $('#edit-exame').find(`[name="${i}"]`).val(data[i]);
            }
        }
    });
});
