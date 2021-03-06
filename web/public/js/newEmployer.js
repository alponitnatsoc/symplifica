
/**
 * Created by gabrielsamoma on 11/11/15.
 */
function startEmployer() {
    var validator;

    $("#errorSeverance").hide();
    $.getScript("//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js").done(function () {
      console.log("1st time");
        $.ajax({
          url: "//ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/additional-methods.min.js",
          dataType: "script",
          timeout: 4000
      }).done(function() {
            console.log("2nd time");
        validator = $("form[name='register_employer']").validate({
            rules: {
                "register_employer[person][documentType]": "required",
                "register_employer[person][document]": {required : true, maxlength : 10},
                "register_employer[person][names]": "required",
                "register_employer[person][lastName1]": "required",
                "register_employer[documentExpeditionDate][day]": "required",
                "register_employer[documentExpeditionDate][month]": "required",
                "register_employer[documentExpeditionDate][year]": "required",
                "register_employer[birthDate][day]": "required",
                "register_employer[birthDate][month]": "required",
                "register_employer[birthDate][year]": "required",
                "register_employer[person][mainAddress]": "required",
                "register_employer[sameWorkHouse]": "required",
                "register_employer[person][department]": "required",
                "register_employer[person][city]": "required",
                "register_employer[workplaces]": "required",
                "register_employer[arl]" : "required"
            },
            messages: {
                "register_employer[person][documentType]": "Por favor selecciona el tipo de documento",
                "register_employer[person][document]": { required :"Por favor ingresa tu documento", maxlength :"El documento no puede ser tan largo"},
                "register_employer[person][names]": "Por favor ingresa tu nombre",
                "register_employer[person][lastName1]": "Por favor ingresa tu primer apellido",
                "register_employer[documentExpeditionDate][day]": "Por favor selecciona un día",
                "register_employer[documentExpeditionDate][month]": "Por favor selecciona un mes",
                "register_employer[documentExpeditionDate][year]": "Por favor selecciona un año",
                "register_employer[person][birthDate][day]": "Por favor selecciona un día",
                "register_employer[person][birthDate][month]": "Por favor selecciona un mes",
                "register_employer[person][birthDate][year]": "Por favor selecciona un año",
                "register_employer[person][mainAddress]": "Por favor ingrese una dirección",
                "register_employer[sameWorkHouse]": "Por favor selecciona una opción",
                "register_employer[person][department]": "Por favor selecciona un departamento",
                "register_employer[person][city]": "Por favor selecciona una ciudad",
                "register_employer[workplaces]": "Por favor ingresa un nombre para tu lugar de trabajo",
                "register_employer[arl]" : "Por favor selecciona una opción"
            }
        });

        $("ul.workplaces input[name*='mainAddress']").each(function () {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Por favor ingresa una dirección",
                }
            });
        });

        $("ul.phones input[name*='phoneNumber']").each(function () {
            $(this).rules("add", {
                maxlength: 10,
                required: true,
                number: true,
                pattern: /3[\d]{9}/,
                messages: {
                    required: "Por favor ingresa un número de teléfono de celular en el siguiente formato, ejemplo 3508330000",
                    number: "Por favor ingresa solo digitos",
                    pattern: "El número no tiene la estructura de un celular colombiano",
                    maxlength: "No es un número de celular válido; ejemplo 3508330000"
                }
            });
        });

        $("ul.workplaces select").each(function () {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Por favor selecciona una opción"
                }
            });
        });
        $("input[class*='autocom']").each(function () {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Por favor escribe en el campo, hasta encontrar la entidad o a la cual te gustaría ser afiliado"
                }
            });
        });
      }).fail(function() {
          location.reload();
      });
    });
    var $collectionHolderPhones;
    var $addPhoneLink = $('<a href="#" class="add_phone_link" style="padding-top:2px !important;padding:10px;color:#00cdcc;text-decoration: none;"><i class="fa fa-plus-circle fa-2x" style="vertical-align: middle; color:#00cdcc;font-size:18px;"></i> <span style="display: inline;font-size:13px">Agregar otro lugar de trabajo</span></a>');
    var $addSeveranceLink = $('<a href="#" class="add_severance_link hidden" style="padding-top:2px !important;padding:10px;color:#00cdcc;text-decoration: none;margin-top:10px;display:block;"><i class="fa fa-plus-circle fa-2x" style="vertical-align: middle; color:#00cdcc;font-size:18px;"></i> <span style="display: inline;font-size:13px;">Agregar otra caja de compensación</span></a>');
    var $newLinkLi = $('<li class="col-md-12 text-center" id="addWorkplace"></li>').append($addPhoneLink);
    var $newLinkSeveranceLi = $('<li id="newSeveranceLinkLi" class="col-md-12 text-center" id="addSeverance"></li>').append($addSeveranceLink);
    var $collectionHolder;
    $collectionHolderPhones = $('ul.phones');
    var $collectionHolderSeverances = $('ul.severances');
    $collectionHolder = $('ul.workplaces');

    $collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this), "lugar de trabajo");
    });
    $collectionHolder.append($newLinkLi);
    $collectionHolderSeverances.append($newLinkSeveranceLi);
    $collectionHolder.data('index', ($collectionHolder.find(':input').length) / 5);
    $collectionHolderSeverances.data('index', ($collectionHolderSeverances.find(':input').length) / 3);

    $addPhoneLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        addPhoneForm($collectionHolder, $newLinkLi);
        var liWorkplace=$("#addWorkplace").prev();
        $(liWorkplace).find('select').filter(function () {
            return this.id.match(/department/);
        }).change(function () {
            var $department = $(this);
            $department.parent().parent().next().find("select[name*='ity']").html("<option value =''>Cargando Ciudades...</option>");
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
                data: {department: $department.val()}
            }).done(function (data) {
                $('#' + citySelectId).html(
                    // ... with the returned one from the AJAX response.
                    jsonToHTML(data)
                );
            });

        });
    });
    /*$addSeveranceLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        addSeveranceForm($collectionHolderSeverances, $newLinkSeveranceLi);
    });*/

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    //el cambio de tabs entre el formulario de registro
    $('.btnPrevious').click(function () {
        $('.nav-justified > .active').prev('li').find('a').trigger('click');
    });
    var redirUri = "";
    $('#btn-save-entities').click(function (e) {
        e.preventDefault();
        var form = $("[name='register_employer']");
        var errors = { register_employer_severances_0_severancesAC: "La entidad no existe en nuestra base de datos, favor revisa su escritura" };
        var severances = [];
        var severancesExist = [];
        var arl = $(form).find("#register_employer_arl");
        var arlEx = $(form).find("#register_employer_arlExists");
        var i = 0;
        var flagValid = true;
        var emptyFlag = false;

        $(form).find("input[name*='register_employer[severances]']").not("[type='hidden']").each(function (index) {
            if (!validator.element($(this))) {
                flagValid = false;
                emptyFlag = true;
            }
            var checkVar = "register_employer_severances_" + index + "_severances";
            var checkVarOpt = "register_employer_severances_" + index + "_severances option";
            var origVar = "register_employer_severances_" + index + "_severancesAC";
            $("#"+checkVar).val($("#"+checkVarOpt).filter(function () { return $.trim($(this).html()) == $.trim($("#"+origVar).val()); }).val());
        });

        //$("#register_employer_severances_0_severances").val($('#register_employer_severances_0_severances option').filter(function () { return $.trim($(this).html()) == $.trim($('#register_employer_severances_0_severancesAC').val()); }).val());
        i = 0;
        $(form).find("select[name*='register_employer[severances]']").each(function () {
          if($(this).parent().parent().attr("class") == "hidden"){
            if (!validator.element($(this))) {
                flagValid = false;
            }
            severances[i++] = $(this).val();
            if($(this).val() == undefined || $(this).val() == "" && emptyFlag == false){
              flagValid = false;
              $("#errorSeverance").show();
            }
          }
        });

        if(flagValid) {
          $("#errorSeverance").hide();
        }

        i = 0;
        $(form).find(".existsS").find("input[type=radio]").each(function () {
            if($(this).is(":checked")){
              severancesExist[i++] = $(this).val();
            }
        });
        //alert(flagValid);
        if (!flagValid) {
            return;
        }
        if (!( validator.element(arl) )) {
            return;
        }

        var arlExists;
        $("#register_employer_arlExists").find("input[type=radio]").each(function () {
          if($(this).is(":checked")){
            arlExists = $(this).val();
          }
        });

        $('#createdModal').modal('toggle');
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {
                severances: 			severances,
                severancesExists: severancesExist,
                arl: 					arl.val(),
                arlExists: arlExists,
                economicalActivity: 	$(form).find("input[name='register_social_security[economicalActivity]']").val(),
            }
        }).done(function (data) {
            if (data["url"] != null) {
                history.pushState("","",data["url"]);
                redirUri = data["url"];
            } else {
                $('#main').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(data).find('#main'));
                addClick();
                if (!jsLoader(url)) {
                    addSumbit();
                }
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if(jqXHR==errorHandleTry(jqXHR)){
                alert(jqXHR + "Server might not handle That yet" + textStatus + " " + errorThrown);
            }
        });
        $("#employerDismiss").on('click', function () {
            window.location.href = redirUri;
        });
    });
    $('#btn-inquiry').click(function (e) {
        e.preventDefault();
        var documentType = $(form).find("select[name='register_employer[person][documentType]']");
        var document = $(form).find("input[name='register_employer[person][document]']");
        var lastName1 = $(form).find("input[name='register_employer[person][lastName1]']");
        //if (!form.valid()) {
        //    return;
        //}
        if (!(validator.element(documentType) && validator.element(document) && validator.element(lastName1))) {
            //    alert("Llenaste algunos campos incorrectamente");
            return;
        }

        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {
                documentType: documentType.val(),
                document: document.val(),
                lastName1: lastName1.val(),
            }
        }).done(function () {
            alert("La cédula que nos proporcionaste ya existe en nuestro sistema, intenta buscando en tus cuentas de correo electrónico algún email de symplifica");
            sendAjax("/dashboard");

        }).fail(function (jqXHR, textStatus, errorThrown) {
            //show the other stuf
        });
    });
    $('#btn-1').click(function (e) {
        e.preventDefault();
        var form = $("[name='register_employer']");
        var documentType = $(form).find("select[name='register_employer[person][documentType]']");
        var document = $(form).find("input[name='register_employer[person][document]']");
        var names = $(form).find("input[name='register_employer[person][names]']");
        var lastName1 = $(form).find("input[name='register_employer[person][lastName1]']");
        var lastName2 = $(form).find("input[name='register_employer[person][lastName2]']");
        if (!form.valid()) {
            return;
        }
        //if (!(validator.element(documentType) && validator.element(document) && validator.element(names) && validator.element(lastName1))) {
        //    alert("Llenaste algunos campos incorrectamente");
        //    return;
        //}

        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {
                youAre: $(form).find("input[name='register_employer[youAre]']:checked").val(),
                documentType: documentType.val(),
                document: document.val(),
                names: names.val(),
                lastName1: lastName1.val(),
                lastName2: lastName2.val(),
                documentExpeditionDateYear: $(form).find("select[name='register_employer[documentExpeditionDate][year]']").val(),
                documentExpeditionDateMonth: $(form).find("select[name='register_employer[documentExpeditionDate][month]']").val(),
                documentExpeditionDateDay: $(form).find("select[name='register_employer[documentExpeditionDate][day]']").val(),
                year: $(form).find("select[name='register_employer[person][birthDate][year]']").val(),
                month: $(form).find("select[name='register_employer[person][birthDate][month]']").val(),
                day: $(form).find("select[name='register_employer[person][birthDate][day]']").val(),
            }
        }).done(function (data) {
            $('.nav-justified > .active').next('li').find('a').trigger('click');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if(jqXHR==errorHandleTry(jqXHR)){
                alert(jqXHR + "Server might not handle That yet" + textStatus + " " + errorThrown);
            }
        });
    });
    $('#btn-2').click(function (e) {
        e.preventDefault();
        var form = $("[name='register_employer']");
        var idsPhones = [], phones = [];
        var mainAddress = $(form).find("input[name='register_employer[person][mainAddress]']");
        var department = $(form).find("select[name='register_employer[person][department]']");
        var city = $(form).find("select[name='register_employer[person][city]']");
        if (!form.valid()) {
            return;
        }
        //if (!(validator.element(mainAddress) && validator.element(department) && validator.element(city))) {
        //    alert("Llenaste algunos campos incorrectamente");
        //    return;
        //}
        var i = 0;
        $(form).find("ul.phones input[name*='id']").each(function () {
            idsPhones[i++] = $(this).val();
        });
        i = 0;
        var flagValid = true;
        $(form).find("ul.phones input[name*='phoneNumber']").each(function () {
            if (!validator.element($(this))) {
                flagValid = false;
                return;
            }
            phones[i++] = $(this).val();
        });

        if(!validator.element($("#register_employer_person_mainAddress"))) {
          flagValid = false;
          return;
        }

        if (!flagValid) {
            return;
        }
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {
                mainAddress: mainAddress.val(),
                neighborhood: "",
                phonesIds: idsPhones,
                phones: phones,
                department: department.val(),
                city: city.val(),
            }
        }).done(function (data) {
            var selected = $("input[name='register_employer[sameWorkHouse]']:checked").val();
            if (selected == "1") {
                //$('ul.workplaces').hide();
                var select = $("#register_employer_workplaces_0_id");
                $("#register_employer_workplaces_0_name").val("Dirección Principal");
                $("#register_employer_workplaces_0_mainAddress").val($("#register_employer_person_mainAddress").val());
                $("#register_employer_workplaces_0_department").val($("#register_employer_person_department").val());
                var $options = $("#register_employer_person_city").find("> option").clone();
                $("#register_employer_workplaces_0_city").append($options);
                $("#register_employer_workplaces_0_city").val($("#register_employer_person_city").val());

            }
            $('.nav-justified > .active').next('li').find('a').trigger('click');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if(jqXHR==errorHandleTry(jqXHR)){
                alert(jqXHR + "Server might not handle That yet" + textStatus + " " + errorThrown);
            }
        });
    });
    //funcion que agrega un listener a cada department
    addListeners();
    //colocar el select en el valor del tamaño del arreglo
    var dropDownWork = $collectionHolder.data('index');
    if (dropDownWork == 1 && $("input[name='register_employer[sameWorkHouse]']:checked").val() != '0') {
        //$('ul.workplaces').hide();
    }

    $("[name='register_employer']").on("submit", function (e) {
        e.preventDefault();

        var form = $("[name='register_employer']");
        var names = [], addresses = [], citys = [], departments = [], ids = [];
        var i = 0;
        $(form).find("ul.workplaces input[name*='id']").each(function () {
            ids[i++] = $(this).val();
        });
        i = 0;
        var flagValid = true;
        var modal = document.getElementById('exceptionModal');
        $(form).find("ul.workplaces input[name*='mainAddress']").each(function () {
            if (!validator.element($(this))) {
                flagValid = false;
                return;
            }
            addresses[i++] = $(this).val();
        });
        if (!flagValid) {
            //modal.style.display = "block";
            return;
        }
        i = 0;
        $(form).find("ul.workplaces input[name*='name']").each(function () {
            if (!validator.element($(this))) {
                flagValid = false;
                return;
            }
            names[i++] = $(this).val();
        });
        if (!flagValid) {
            //modal.style.display = "block";
            return;
        }
        i = 0;
        $(form).find("ul.workplaces select[name*='city']").each(function () {
            if (!validator.element($(this))) {
                flagValid = false;
                return;
            }
            citys[i++] = $(this).val();
        });

        if (!flagValid) {
            //modal.style.display = "block";
            return;
        }
        i = 0;
        $(form).find("ul.workplaces select[name*='department']").each(function () {
            if (!validator.element($(this))) {
                flagValid = false;
                return;
            }
            departments[i++] = $(this).val();
        });

        if (!flagValid) {
            //modal.style.display = "block";
            return;
        }
        i = 0;

        var sameWorkHouse = $(form).find("input[name='register_employer[sameWorkHouse]']");

        $.ajax({
            url: form.attr('action'),
            type: $(form).attr('method'),
            data: {
                sameWorkHouse: $(form).find("input[name='register_employer[sameWorkHouse]']:checked").val(),
                workId: ids,
                workName: names,
                workMainAddress: addresses,
                workCity: citys,
                workDepartment: departments
            }
        }).done(function (data) {
            var times=0;
            if($("#register_employer_severances_0_severancesAC").val()==""){
                times = parseInt(data["Severances"])-1;
            }else{
                times = parseInt(data["Severances"]);
            }
            var $collectionHolderSeverances = $('ul.severances');
            var newlinliseverance = $("#newSeveranceLinkLi");
            for(var i=0;i<times;i++){
                addSeveranceForm($collectionHolderSeverances, newlinliseverance);
            }


            $('.nav-justified > .active').next('li').find('a').trigger('click');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if(jqXHR==errorHandleTry(jqXHR)){
                alert(jqXHR + "Server might not handle That yet" + textStatus + " " + errorThrown);
            }
        });
    });
}


function jsonToHTML(data) {
    var htmls = "<option value=''>Seleccionar una opción</option>";
    for (var i = 0; i < data.length; i++) {
        htmls += "<option value='" + data[i].id_city + "'>" + data[i].name + "</option>";
    }
    return htmls;
}
function addListeners() {
    var documentType = $("#register_employer_person_documentType");
    var document = $("#register_employer_person_document");
    var lastName1 = $("#register_employer_person_lastName1");
    $(documentType).blur( function () {
        if(documentType.val()!=""&&document.val()!=""&&lastName1.val()!=""){
            checkExistance();
        }
    });
    $(document).blur( function () {
        if(documentType.val()!=""&&document.val()!=""&&lastName1.val()!=""){
            checkExistance();
        }
    });
    $(lastName1).blur( function () {
        if(documentType.val()!=""&&document.val()!=""&&lastName1.val()!=""){
            checkExistance();
        }
    });
    $("#documentExistent").on('click', function () {
        window.location.href = "/dashboard";
    });
    $('select').filter(function () {
        return this.id.match(/department/);
    }).change(function () {
        var $department = $(this);
        $department.parent().parent().next().find("select[name*='ity']").html("<option value =''>Cargando Ciudades...</option>");
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
            data: {department: $department.val()}
        }).done(function (data) {
            $('#' + citySelectId).html(
                    // ... with the returned one from the AJAX response.
                    jsonToHTML(data)
                    );
        });

    });
    $("input[name='register_employer[sameWorkHouse]']").change(function () {
        var selected = $("input[name='register_employer[sameWorkHouse]']:checked").val();
        if (selected == "1") {
            //$('ul.workplaces').hide();
            var select = $("#register_employer_workplaces_0_id");
                $("#register_employer_workplaces_0_name").val("Mi Casa");
                $("#register_employer_workplaces_0_mainAddress").val($("#register_employer_person_mainAddress").val());
                $("#register_employer_workplaces_0_department").val($("#register_employer_person_department").val());
                $("#register_employer_workplaces_0_city").val($("#register_employer_person_city").val());

        } else {
            $('ul.workplaces').show();
            if ($("#register_employer_workplaces_0_mainAddress").val() == $("#register_employer_person_mainAddress").val()) {
                $("#register_employer_workplaces_0_name").val("");
                $("#register_employer_workplaces_0_mainAddress").val("");
                $("#register_employer_workplaces_0_department").val("");
                $("#register_employer_workplaces_0_city").val("");
            }
        }
    })
    var selected = $("input[name='register_employer[sameWorkHouse]']:checked").val();
    if (selected == "1") {
        //$('ul.workplaces').hide();
        var select = $("#register_employer_workplaces_0_id");
        $("#register_employer_workplaces_0_name").val("Mi Casa");
        $("#register_employer_workplaces_0_mainAddress").val($("#register_employer_person_mainAddress").val());
        $("#register_employer_workplaces_0_department").val($("#register_employer_person_department").val());
        $("#register_employer_workplaces_0_city").val($("#register_employer_person_city").val());

    }
    initEntitiesEmployerFields();
    restrictARL(true);
    $( "#register_employer_arlExists" ).change(function( event ) {
        restrictARL(false);
    });

}
function addPhoneForm($collectionHolderB, $newLinkLi) {
    var prototype = $collectionHolderB.data('prototype');
    var index = $collectionHolderB.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolderB.data('index', index + 1);
    var $newFormLi = $('<li class="col-sm-12"></li>').append(newForm);
    addTagFormDeleteLink($newFormLi, "lugar de trabajo");
    $newLinkLi.before($newFormLi);
    $(newForm).find("select");
}
function addSeveranceForm($collectionHolderB, $newLinkLi) {
    var prototype = $collectionHolderB.data('prototype');
    var index = $collectionHolderB.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolderB.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    var dataSev=[];
    $(newForm).find("select").find("> option").each(function() {
        dataSev.push({'label':this.text,'value':this.value});
    });
    var $actualInsertion=$newLinkLi.prev().find("input[name*='register_employer[severances]']").not("[type='hidden']").not("[name*='severancesExists']");
    addAutoComplete($actualInsertion, dataSev);
    $actualInsertion.rules("add", {
        required: true,
        messages: {
            required: "Por favor, escribe el nombre de la entidad"
        }
    });
}
function addTagFormDeleteLink($tagFormLi, $tipo) {
    var $removeFormA = $('<a href="#" class="col-sm-5 col-xs-8 remove_phone_link" style="padding:10px;color:#fd5c5c;text-decoration: none;"><i class="fa fa-minus-circle " style="color:#fd5c5c;max-width: 30px;"></i> Eliminar esta dirección de trabajo</a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}
function checkExistance(){
    var documentType = $("#register_employer_person_documentType");
    var document = $("#register_employer_person_document");
    var lastName1 = $("#register_employer_person_lastName1");
    $.ajax({
        url: "/api/public/v1/inquiries/documents",
        type: 'POST',
        data: {
            documentType: documentType.val(),
            document: document.val(),
            lastName1: lastName1.val(),
            personType: '1'
        }
    }).done(function (data) {
        console.log(data);
        if(!(data["idEmployer"]=="-1"||data["idEmployer"]=="-2")){
            $("#documentExistent").modal("show");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        //show the other stuf
    });

}
function addAutoComplete(autoTo, data){
    $(autoTo).autocomplete({
        source: function(request, response) {
            var results;
            if(request.term.length != 0){
              results = $.ui.autocomplete.filter(data, request.term);
            }
            else {
              results = $.ui.autocomplete.filter("", request.term);
            }
            response(results.slice(0, 5));
        },                minLength: 0,
        select: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            $($(this).parent()).parent().find("select").each(function() {
              if($(this).parent().parent().attr("class") == "hidden"){
                $(this).val(ui.item.value);
              }
            });
        },
        focus: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            $($(this).parent()).parent().find("select").each(function() {
              if($(this).parent().parent().attr("class") == "hidden"){
                $(this).val(ui.item.value);
              }
            });
        }

    });
    $(autoTo).on("focus",function () {
        $(autoTo).autocomplete("search", $(autoTo).val());
    });

}
function initEntitiesEmployerFields(){
    var dataSev=[];
    $("#register_employer_severances_0_severances").find("> option").each(function() {
        dataSev.push({'label':this.text,'value':this.value});
    });
    $(".autocomS").each(function () {
        addAutoComplete($(this), dataSev);
    });
}

function restrictARL(firstTime){
  var selection = "";
  var arlSelection;
  $("#register_employer_arlExists").find("input[type=radio]").each(function () {
    if($(this).is(":checked") ){
      arlSelection = $(this).val();
    }
  });

  if(arlSelection==0){
      $("#msgARL").hide();
      if(!firstTime){
          $('#register_employer_arl').attr("disabled",false);
          $('#register_employer_arl').val('').change();
      }
  }else {
        $("#msgARL").show();
        $('#register_employer_arl').attr("disabled",true);
        $('#register_employer_arl').val('48').change();
  }
}
