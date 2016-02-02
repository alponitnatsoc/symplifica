$(document).ready(function () {
    $('.employee .pay').on('change', function (e) {
        var i = 0;
        $('.employee .pay').each(function (index, element) {
            if ($(element)[0].checked) {
                i++;
            }
        });
        $('#btnCalculate').prop('disabled', i == 0);
    });
    $('.btn-add-novelty').on('click', function (event) {
        event.preventDefault();
        //button = $(event.relatedTarget);
        //employeeName = button.data('data-employee-name');
        employeeName = $(this).attr('data-employee-name');
        url = $(this).attr('href');
        $.ajax({
            url: url,
            //data: data,
            //success: success,
            //dataType: dataType,
            beforeSend: function (xhr) {
                $('#modal_loader').modal('show');
            }
        }).done(function (data) {
            //$('#modal_body_add_novelty').html($(data).find('#main'));
            $('#modal_body_add_novelty').replaceWith($(data).find('#main')); // ... with the returned one from the AJAX response.
            $('#modal-title_add_novelty').html("Reporte aquí cada hecho que esté asociado a " + employeeName + " para que sea considerado en el cálculo de su pago.");
            $('#modal_add_novelty').modal('show');

            //$('.result_ajax').html(name + " fue " + state + " exitosamente.").show(1000);
            //setTimeout(function () {
            //    $('.result_ajax').html("").hide(1000);
            //}, 2000);
        }).fail(function (data) {
            //$("#modal_body_add_novelty").html(data);
        }).always(function () {
            $('#modal_loader').modal('hide');
        });
        //document.location = "/novelty/select/" + payroll;
    });
});