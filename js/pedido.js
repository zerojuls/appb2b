/*
 * Orders Functions
 */

var TituloInformacion = "Global Store";
var rootPath = "http://"+document.domain+'/gsb2b/';

function updateOrderItem(obj){
    str = obj.value;
    charNum = str.substring(str.length-1,str.length);
    if(!isNaN(charNum) && charNum!=' '){
        subtotal = $('#precio_'+obj.id).html()*obj.value;
        $('#subtotal_'+obj.id).html(parseFloat(subtotal).toFixed(2));
        updateTotalOrder();
    }else{
        obj.value = str.substring(0,str.length-1);
    }
}

function updateTotalOrder(){
    total = 0;
    $("input[name='producto[]']").each(function(){
        cant = $("#"+this.value).val();
        if(!isNaN(cant)){
            total += cant * $("#precio_"+this.value).html();
        }
    });
    $("#precio_total").html(parseFloat(total).toFixed(2));
}

function delRow(item){
    answer = confirm('Desea eliminar el producto '+item+'?');
    if(answer){
        $("#item_"+item).remove();
        updateTotalOrder();
    }
}

function saveOrder(answ){
    var URL = rootPath+"pedido/update_pedido";
    $.ajax({
        type: "POST",
        dataType: "html",
        data: {'csrf_test_name' : $("#csrf_test_name").val(),
               'codigo' : $("input[name='codigo[]']").serializeArray(),
               'cantidad' : $("input[name='cantidad[]']").serializeArray()
              },
        url: URL,
        beforeSend: function(){
            $("#btnSave").attr("disabled", "disabled");
            $("#btnSave").html("Procesando ...");
        },
        success: function() {
            if(!answ)
                jAlert('Se guardó satisfactoriamente!', TituloInformacion);
        },
        error: function() {
            jAlert('Surgió un error al guardar el pedido!', TituloInformacion);
        },
        complete: function() {
            $("#btnSave").removeAttr("disabled");
            $("#btnSave").html("Guardar");
        }
    });
}

function confirmOrder(){
    saveOrder(true);
    var URL = rootPath+"pedido/confirm_pedido";
    var param = {'csrf_test_name' : $("#csrf_test_name").val()};
    $.openModal('#btnConfirm', URL, '#dlgNew', param, "Procesando ...");
}
function cancelOrder(){
    
}


