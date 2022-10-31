$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


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


    $(document).on('click', '[data-bs-target="#edit-exame"]', function () {
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

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();

        var btn = $(this);

        Swal.fire({
            icon: 'info',
            title: 'Gostaria de apagar esse exame?',
            showCancelButton: true,
            confirmButtonText: 'SIM',
            cancelButtonText: 'NÃO',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = btn.attr('href');
            }
        });
    });
    $(document).on('click', '#btn-aceitar', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Tem certeza que deseja aceitar esse pedido?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, aceitar',
            cancelButtonText: 'Não, cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                value = 1;
                $.ajax({
                    url: `recived/${$(this).data('id')}`,
                    type: 'POST',
                    data: { value },
                    success: (data) => {
                        console.log(data);
                        window.location.reload();

                    }
                });

                Swal.fire(
                    'Aceitado!',
                    'Esse pedido foi aceito!',
                    'success'
                )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Cancelado',
                    'Esse pedido não foi aceito :)',
                    'error'
                )
            }
        })

    });

    $('.js-example-basic-single').select2({
        width: '100%',
    });
    $('#cep').mask('99999-999');
    $('#fone').mask('(99) 99999-9999');
    $('#cell').mask('(99) 99999-9999');

    $(document).on('blur keyup', '#cep', function () {
        cep = $(this).val();
        $.ajax({
            type: 'get',
            url: "/cep-get/",
            data: { cep: cep },
            success: function (data) {
                console.log(data);
                $('#address').val(data.logradouro);
                $('#bairro').val(data.bairro);
                $('#cidade').val(data.localidade);
                $('#uf').val(data.uf);
            }
        });
    });

    $(document).on('click', '#owner-save', function () {
        console.log('cliec');
        var btn = $(this);
        var form = $('#owner-form').serialize();
        var url = $('#owner-form').attr('action');
        btn.html('<div class="spinner-border text-light" role="status"></div>');
        btn.prop('disabled', true);
        $('#owner-form').find('input').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: form,
            success: (data) => {
                console.log(data);
                Swal.fire(
                    'Sucesso!',
                    'Proprietario cadastrado com sucesso!',
                    'success'
                )
                window.location.reload();

                // window.location.href = data;
            },
            error: (err) => {
                // console.log(err);
                btn.html('Salvar');
                btn.prop('disabled', false);
                $('#owner-form').find('input').prop('disabled', false);

                Swal.fire({
                    icon: 'error',
                    title: err.responseJSON.invalid
                });
            }
        });
    });

    $("#cpfcnpj").keydown(function () {
        try {
            $("#cpfcnpj").unmask();
        } catch (e) { }

        var tamanho = $("#cpfcnpj").val().length;

        if (tamanho < 11) {
            $("#cpfcnpj").mask("999.999.999-99");
        } else {
            $("#cpfcnpj").mask("99.999.999/9999-99");
        }

        // ajustando foco
        var elem = this;
        setTimeout(function () {
            // mudo a posição do seletor
            elem.selectionStart = elem.selectionEnd = 10000;
        }, 0);
        // reaplico o valor para mudar o foco
        var currentValue = $(this).val();
        $(this).val('');
        $(this).val(currentValue);
    });
});
