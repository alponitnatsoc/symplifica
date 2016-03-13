/**
 * Created by gabrielsamoma on 2/8/16.
 */
function addNoveltySelectListener() {
    var modBody=$("#noveltyModal").find(".cuerpoNovelty");
    var form=modBody.find("form");
    console.log(form);
    $("#novelty_fields_date_start").on("change", function(){

    });
    $("#novelty_fields_date_end").on("change", function(){

    });
    form.submit(function (e) {
        e.preventDefault();
        var value=form.find("input[name='form[noveltyType]']:checked").val();
        var valueText=form.find("input[name='form[noveltyType]']:checked").parent().text();
        if($("#novelty_fields_noveltyType").val() !=null){
            value="";
        }
        if(valueText ==" Vacaciones"){
            //calculate days
            $("#novelty_fields_date_start")
        }
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
                if(error.length!=0){
                    alert(error.html());
                }
                modBody.html(
                    innerForm
                );
                addNoveltySelectListener();
            }
        });
    });
}
function startNovelty(){
    hideAll();
    $("input[name='form[noveltyTypeGroup]']").change(function(){
        var selectedVal = $("input[name='form[noveltyTypeGroup]']:checked");
        hideAll();
        var group=$("."+selectedVal.val());
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
    $("input[name='form[noveltyTypeGroup]']").each(function(){

        $("."+$(this).val()).each(function(){
            $(this).hide();
        });
    })
}