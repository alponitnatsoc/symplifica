/**
 * Created by gabrielsamoma on 2/8/16.
 */
function addNoveltySelectListener() {

    $("#notaNovedad").hide();

    var modBody=$("#noveltyModal").find(".cuerpoNovelty");
    var form=modBody.find("form");

    var selectedText = $("#novelty_fields_noveltyType").find("option:selected").text();
    $(".toHide").hide();
    $("#nombreNovedad").text(selectedText);
    if(selectedText =="Hora extra diurna"){
      $("#notaNovedad").show();
      $("#notaNovedad").text("Hora extra diurna es el tiempo de trabajo adicional a las 8 horas diarias o a la jornada pactada en un horario de 6 A.M. y las 10 P.M.");
    }

    if(selectedText =="Hora extra festiva diurna"){
      $("#notaNovedad").show();
      $("#notaNovedad").text("Hora extra festiva diurna es el tiempo de trabajo adicional a las 8 horas diarias o a la jornada pactada en un horario entre las  6 A.M. y las 10 P.M. en un día festivo.");
    }

    if(selectedText =="Hora extra nocturna"){
      $("#notaNovedad").show();
      $("#notaNovedad").text("Hora extra nocturna es el tiempo de trabajo adicional a las 8 horas diarias o a la jornada pactada en un horario entre las 10 P.M. y las 6 A.M.");
    }

    if(selectedText =="Hora extra festiva nocturna"  ){
      $("#notaNovedad").show();
      $("#notaNovedad").text("Hora extra festiva nocturna es el tiempo de trabajo adicional a las 8 horas diarias o a la jornada en un horario entre las 10 P.M. y las 6 A.M. en un día festivo.");
    }

    if(selectedText =="Licencia de maternidad" ||
        selectedText =="Licencia de paternidad" ||
          selectedText =="Incapacidad general"){
          $("#notaNovedad").show();
          $("#notaNovedad").html("Esta información debes obtenerla del comprobante otorgado por la EPS.</br>Por ahora, no realizamos el proceso de cobro, debes contactar directamente a la EPS para obtener el dinero de la incapacidad.");
    }

    if(selectedText == "Licencia remunerada"){
      $("#notaNovedad").show();
      $("#notaNovedad").html("Esta licencia la otorgas por autorización y criterio tuyo bajo un permiso solicitado por tu empleado o por una calamidad doméstica justificada");
    }

    if(selectedText == "Incapacidad laboral"){
      $("#notaNovedad").show();
      $("#notaNovedad").html("Esta información debes obtenerla del comprobante otorgado por la ARL.</br>Por ahora, no realizamos el proceso de cobro, debes contactar directamente a la ARL para obtener el dinero de la incapacidad.");
    }

    if(selectedText == "Suspensión"){
      $("#notaNovedad").show();
      $("#notaNovedad").html("Posibles razones para suspender a tu empleado:</br>&#10148;Incumplimiento de horario.</br>&#10148;Inasistencia laboral no justificada.</br>&#10148;Incumplimiento de sus funciones de contrato.");
    }

    if(selectedText == "Bonificación"){
      $("#notaNovedad").show();
      $("#notaNovedad").html("Recuerda que las bonificaciones recurrentes se vuelven parte del salario.");
    }

    if($("#novelty_fields_noveltyType").find("option:selected").text()=="Vacaciones"){
        $("#notaNovedad").show();
        $("#notaNovedad").text("Actualmente no tramitamos vacaciones adelantadas.");
        $("#novelty_fields_date_start").on("change", function(){
            var $dateStart=$(this);
            var $dateEnd=$("#novelty_fields_date_end");
            if(checkDates($dateStart)&&checkDates($dateEnd)){
                if(!($("#daysAmountText").length)){
                    var p = "<p id='daysAmountText'>Número de días a tomar entre las fechas escogidas: <div id='daysAmount'></div> </p>";
                    $("#novelty_fields").append(p);
                }
                $.ajax( {
                    type: "GET",
                    url: "/api/public/v1/valids/"+$dateStart.find("select[name*='year']").val()+"-"
                    +$dateStart.find("select[name*='month']").val()+"-"+$dateStart.find("select[name*='day']").val()+
                    "/vacations/"+$dateEnd.find("select[name*='year']").val()+"-"
                    +$dateEnd.find("select[name*='month']").val()+"-"+$dateEnd.find("select[name*='day']").val()+
                    "/days/-1/contracts/"+$(form).find("#novelty_fields_idPayroll").val(),
                }).done(function(data){
                    $("#daysAmount").html(data["days"]);
                }).fail(function(x,y,z){

                });
            }
        });
        $("#novelty_fields_date_end").on("change", function(){
            var $dateEnd=$(this);
            var $dateStart=$("#novelty_fields_date_start");
            if(checkDates($dateStart)&&checkDates($dateEnd)){
                if(!($("#daysAmountText").length)){
                    var p = "<p id='daysAmountText'>Número de días a tomar entre las fechas escogidas: <div id='daysAmount'></div> </p>";
                    $("#novelty_fields").append(p);
                }
                $.ajax( {
                    type: "GET",
                    url: "/api/public/v1/valids/"+$dateStart.find("select[name*='year']").val()+"-"
                    +$dateStart.find("select[name*='month']").val()+"-"+$dateStart.find("select[name*='day']").val()+
                    "/vacations/"+$dateEnd.find("select[name*='year']").val()+"-"
                    +$dateEnd.find("select[name*='month']").val()+"-"+$dateEnd.find("select[name*='day']").val()+
                    "/days/-1/contracts/"+$(form).find("#novelty_fields_idPayroll").val(),
                }).done(function(data){
                    $("#daysAmount").html(data["days"]);
                }).fail(function(x,y,z){

                });
            }
        });
    }
    $("#novelty_fields_date_start").on("change", function(){

    });
    $("#novelty_fields_date_end").on("change", function(){

    });
    form.submit(function (e) {
        e.preventDefault();

        var value=form.find("input[name='form[noveltyType]']:checked").val();
        if($("#novelty_fields_noveltyType").val() !=null){
            value="";
        }

        if(value == 37){
           window.location.href = '/liquidations/final/' + $.trim($("#empId").text()) ;
        }
        else {
          $.ajax( {
              type: "POST",
              url: form.attr( 'action' )+value,
              data: form.serialize()
          }).done(function(data){
              var innerForm=$(data).find("#formForm")
              if(innerForm.find("form").length==0){
                  modBody.html(
                      "<p><h3>Novedad Creada Exitosamente</h3></p>"
                  );
              }else{
                  var error=$(data).find("#error");
                  /*if(error.length!=0){
                      alert(error.html());
                  }*/
                  modBody.html(
                      innerForm
                  );
                  addNoveltySelectListener();
              }
          });
        }
    });
}
function checkDates(date){
    var year=$(date).find("select[name*='year']");
    var month=$(date).find("select[name*='month']");
    var day=$(date).find("select[name*='day']");
    if(year.val()==""||month.val()==""||day.val()==""){
        return false;
    }
    return true;
}
function startNovelty(){
    hideAll();
    $("input[name='form[noveltyTypeGroup]']").change(function(){
        var selectedVal = $("input[name='form[noveltyTypeGroup]']:checked");
        hideAll();
        var fixedString = selectedVal.val().replace(" ",".");
        var group=$("." + fixedString);
        if(group.length==1){
            group.each(function(){
                $(this).show();
                $(this).find("input").prop( "checked", true );
            });
        }else{
            group.each(function(){
                $(this).show();
            });
        }
    })
    addNoveltySelectListener();
}
function hideAll(){
    $("#empId").hide();
    $("input[name='form[noveltyTypeGroup]']").each(function(){
        var toSelect = $(this).val();
        var fixedString = toSelect.replace(" ",".");
        $("." + fixedString).each(function(){
            $(this).hide();
        });
    })
}
