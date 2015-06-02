<style>
.soles{font-size: 18px;}
.height_medium {height: 107px;}
.cantidad{ font-size: 40px !important; margin-right: 80% !important;}
.content.numbers h3 {font-size: 25px;}
@media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important;}}
.btn.btn-secondary {background-image: none;margin-top: -4px;margin-right: -12px;}
.btn.btn-secondary:hover {background-image: none;}
.btn-large {padding: 15px 5px;font-size: 22px;}
.thumbnail.small {max-width: 85px;}
[class*="span"] {padding-left: 0;}
</style>
<style>    
.btn.btn-secondary {
    margin-right: 0;
}
@media (min-width: 1024px) {.row-fluid:not(.fluid) .span3 {width: 33%;}}
</style>
<script type="text/javascript">
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
    return total;
}
function delRow(item){
    jConfirm('Desea eliminar el producto '+item+' de su compra?',TituloInformacion, function(answer) {
        if(answer){
            $("#item_"+item).remove();
            total = updateTotalOrder();
            if(total){
                saveOrder(false);
            }else{
                deleteOrder();
            }
        }
    });
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
            if(!answ){
                jAlert('Se guardo satisfactoriamente!', TituloInformacion);
            }else{
                var URL = rootPath+"pedido/confirm_pedido";
                var param = {'csrf_test_name' : $("#csrf_test_name").val()};
                $.openModal('#btnConfirm', URL, '#dlgNew', param, "Procesando ...");
            }
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
}
function cancelOrder(){
    jConfirm('Esta seguro de eliminar este pedido?', TituloInformacion, function(answer) {
        if(answer){
            deleteOrder();
        }
    });
}
function deleteOrder(){
    var URL = rootPath+"pedido/cancel_pedido";
    var send = true;
    $.ajax({
        type: "POST",
        dataType: "html",
        data: {'csrf_test_name' : $("#csrf_test_name").val(),
              },
        url: URL,
        beforeSend: function(){
            $("#btnCancel").attr("disabled", "disabled");
            $("#btnCancel").html("Procesando ...");
        },
        success: function(response) {
            jAlert(response, TituloInformacion,function(){window.location.href = rootPath+'pedido/';});
        },
        error: function(){
            send = false;
            jAlert('Surgió un error al eliminar el pedido!', TituloInformacion);
        },
        complete: function() {
            if(!send){
                $("#btnCancel").removeAttr("disabled");
                $("#btnCancel").html("Cancelar");
            }
        }
    });
}
</script>
<div class="row-fluid">
    <div class="span3">
        <div class=" box color_2 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Asignado</h3>
                    <div class="row-fluid description mb5"><?php echo $nombre_categoria; ?></div>
                </div>
                <div class="row-fluid span6 width4"> 
                    <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php echo $monto_asignado; ?></h3>
                </div> 
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="box color_3 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Disponible</h3>
                    <div class="description mb5"><?php echo $nombre_categoria; ?></div>
                </div>
                <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php echo($monto_asignado-$monto_acumulado); ?></h3>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class=" box color_17 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Consumido</h3>
                    <div class="description mb5"><?php echo $nombre_categoria; ?></div>
                </div>
                <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php if(isset($monto_acumulado)) echo $monto_acumulado; else echo '0.00';?></h3>
            </div>
        </div>
    </div>
</div>
<div id="main_container">
    <div class="row-fluid">
        <h1>Carro de Compras <i class=" icon-shopping-cart"></i></h1>
        <?php if($monto_pedido>0){ ?>
        <a id="btnConfirm" href="#" onclick="confirmOrder()" class="btn btn-secondary color_19" data-toggle="modal">Confirmar</a>
        <a id="btnSave" href="#" onclick="saveOrder()" class="btn btn-secondary color_19" data-toggle="modal">Guardar</a>
        <?php } ?>
        <a href="<?php echo base_url('pedido/listar_productos/'.$id_categoria); ?>" class="btn btn-secondary color_14">Seguir comprando</a>
        <?php if($monto_pedido>0){ ?>
        <a id="btnCancel" href="#" onclick="cancelOrder()" class="btn btn-secondary color_14">Cancelar Pedido</a>
        <?php } ?>
        <br/>
        <div>Si ha realizado algún cambio en los campos sin hacer clic en el botón Guardar, los cambios se perderán.</div>
        <br/>
        <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <!-- Div donde cargarán los fomularios -->
        </div>
        <input type="hidden" id="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <div class="box color_light">
            <table id="datatable_example" class="responsive table table-condensed table-striped table-bordered" style="width:100%;margin-bottom:0; ">
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>&nbsp;</th>
                </tr>
                <?php
                $total = 0;
                foreach($data as $fila){
                    $subtotal = ($fila['CANTIDAD']*$fila['PRECIO']);
                    $total = $total + $subtotal;
                ?>
                <tr id="item_<?php echo $fila['CODIGO_PRODUCTO']; ?>">
                    <td><input type="hidden" name="codigo[]" value="<?php echo $fila['ID_PRODUCTO']; ?>" /><?php echo $fila['CODIGO_PRODUCTO']; ?></td>
                    <td><input type="hidden" name="producto[]" value="<?php echo $fila['CODIGO_PRODUCTO']; ?>" /><?php echo $fila['NOMBRE_PRODUCTO']; ?></td>
                    <td><input type="text" name="cantidad[]" onkeyup="updateOrderItem(this)" id="<?php echo $fila['CODIGO_PRODUCTO']; ?>" value="<?php echo $fila['CANTIDAD']; ?>" /></td>
                    <td><?php echo $fila['UNIDAD']; ?></td>
                    <td align="right">S/. <span id="precio_<?php echo $fila['CODIGO_PRODUCTO']; ?>"><?php echo number_format($fila['PRECIO'],2); ?></span></td>
                    <td>S/. <span id="subtotal_<?php echo $fila['CODIGO_PRODUCTO']; ?>"><?php echo number_format($subtotal,2); ?></span></td>
                    <td><button class="btn btn-mini" onclick="delRow('<?php echo $fila['CODIGO_PRODUCTO']; ?>')" type="button"><i class="icon-remove"></i></button></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="5" align="right">Total</td>
                    <td align="right">S/. <span id="precio_total"><?php echo number_format($total,2); ?></span></td>
                </tr>
            </table>
        </div>
    </div>
    <!-- End .row-fluid --> 
</div>