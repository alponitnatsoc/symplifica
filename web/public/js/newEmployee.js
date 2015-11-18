
/**
 * Created by gabrielsamoma on 11/11/15.
 */
function startEmployee(){

    $('.btnNext').click(function(){
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
    });
    $('.btnPrevious').click(function(){
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });

    //funcion que agrega un listener a cada department
    addListeners();


    $("form").on("submit",function(e){
        e.preventDefault();
        var form =$("form");
        $.ajax({
            url : form.attr('action'),
            type: $(form).attr('method'),
            data: {
                documentType: 	$(form).find("select[name='register_employee[person][documentType]']").val(),
                document: 		$(form).find("input[name='register_employee[person][document]']").val(),
                names:			$(form).find("input[name='register_employee[person][names]']").val(),
                lastName1: 		$(form).find("input[name='register_employee[person][lastName1]']").val(),
                lastName2: 		$(form).find("input[name='register_employee[person][lastName2]']").val(),
                civilStatus:    $(form).find("select[name='register_employee[personExtra][civilStatus]']").val(),
                year: 			$(form).find("select[name='register_employee[person][birthDate][year]']").val(),
                month: 			$(form).find("select[name='register_employee[person][birthDate][month]']").val(),
                day: 			$(form).find("select[name='register_employee[person][birthDate][day]']").val(),
                birthCountry: 	$(form).find("select[name='register_employee[personExtra][birthCountry]']").val(),
                birthDepartment:$(form).find("select[name='register_employee[personExtra][birthDepartment]']").val(),
                birthCity: 		$(form).find("select[name='register_employee[personExtra][birthCity]']").val(),

                mainAddress: 	$(form).find("input[name='register_employee[person][mainAddress]']").val(),
                neighborhood: 	$(form).find("input[name='register_employee[person][neighborhood]']").val(),
                phone: 			$(form).find("input[name='register_employee[person][phone]']").val(),
                department: 	$(form).find("select[name='register_employee[person][department]']").val(),
                city: 			$(form).find("select[name='register_employee[person][city]']").val(),
                email:          $(form).find("input[name='register_employee[personExtra][email]']").val(),
                employeeId:     $(form).find("input[name='register_employee[idEmployee]']").val(),
            },
            statusCode:{
                200: function(data){
                    console.log(data);
                    sendAjax(data);
                },
                400 : function(data, textStatus, errorThrown){
                    alert("400 :"+errorThrown+"\n"+data.responseJSON.error.exception[0].message);
                    console.log(data);
                    console.log(textStatus);
                    console.log(errorThrown);
                }

            }
        });
    });
}


function jsonToHTML(data) {
    var htmls="<option value=''></option>";
    for(var i=0;i<data.length;i++){
        htmls+="<option value='"+data[i].id_city+"'>"+data[i].name+"</option>";
    }
    return htmls;
}
function addListeners() {
    $('select').filter(function() {
        return this.id.match(/department/);
    }).change(function() {
        var $department = $(this);
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected department value.
        var data = {};
        data[$department.attr('name')] = $department.val();
        var citySelectId = $department.attr("id").replace("_department", "_city");
        // Submit data via AJAX to the form's action path.
        $.ajax({
            method: "POST",
            url: "/api/public/v1/cities",
            data: { department: $department.val()}
        }).done(function(data){
            $('#'+citySelectId).html(
            // ... with the returned one from the AJAX response.
            jsonToHTML(data)
        );});

    });
}