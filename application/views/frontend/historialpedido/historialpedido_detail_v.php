<style>
        .modal-header {background-image: none; height: 50px;}                    
        @media (max-width: 480px) {.modal {left: 0}}
        //  @media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important}}
        .close {font-size: 40px}
        .box .content {padding: 0 15px;}
        // @media (max-width: 480px) {.display_none {display: none;}}
</style>

<div class="box color_light" >
    <div class="modal-header">
        <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
        <h4> <i class="icon-book"></i>
            <span>Detallado del Pedido</span>
        </h4>
    </div>
    <div class="content">
        <!--<br>-->
        <ul class="nav nav-tabs" id="tabExample1">
            <li class="active"><a class="text_color_0" data-toggle="tab" href="#detalle">Detalle</a></li>
            <li><a class="text_color_0" data-toggle="tab" href="#tracking">Tracking</a></li>
        </ul>
        <div class="tab-content">
            <div id="detalle" class="tab-pane fade in active">
                <div class="row-fluid">
                    <div class="form-row control-group row-fluid fluid span5">
                        <div class="span2">
                            <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; ">Nro Pedido</label>
                        </div>
                        <div class="span4">
                            <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['codigo_pedido'])) ? $arrCabecera[0]['codigo_pedido'] : 'null';  ?>">
                        </div>
                        <div class="span2">
                            <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; margin-top: 5px;">Monto</label>
                        </div>
                        <div class="span4">
                            <input class="row-fluid" type="text" disabled="" value="S/. <?php echo (isset($arrCabecera[0]['total_pedido'])) ? $arrCabecera[0]['total_pedido'] : 'null';  ?>">
                        </div>
                    </div>
                    <div class="form-row control-group row-fluid fluid span4">
                        <div class="span2">
                            <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; margin-top: 5px;">Fecha</label>
                        </div>
                        <div class="span10">
                            <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['fecha_pedido'])) ? $arrCabecera[0]['fecha_pedido'] : 'null';  ?>">
                        </div>
                    </div>
                    <div class="form-row control-group row-fluid fluid span3">
                        <div class="span2">
                            <label class="control-label" for="normal-field" style="margin-left: -10px; text-align: left; margin-bottom: -3px; margin-top: 5px;">Estado</label>
                        </div>
                        <div class="span10">
                            <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['estado_pedido'])) ? $arrCabecera[0]['estado_pedido'] : 'null';  ?>">
                        </div>
                    </div>
                </div>
                <form id="frmNew" action="<?php echo base_url('historialpedido/repeat_pedido') ?>" method="POST" class="form-horizontal row-fluid">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="controls hide"><input type="text" class="hide" id="txt_id" name="txt_id" value="<?php if (isset($id_pedido)) echo $id_pedido ?>"></div>
                    <div id="list1">
                    </div>
                    <div class="form-actions row-fluid">
                        <div class="span3 visible-desktop"></div>
                        <div class="span8 ">
                            <?php
                           if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'cliente') { 
                                echo '<button id="submit" class="btn btn-primary" type="submit">Pedir Nuevamente</button>';
                            }
                            ?>
                            <button id="close" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                    <div id="msgForm"></div>
                </form>
            </div>
            <div id="tracking" class="tab-pane fade">
                <div class="row-fluid">
                    <div class="form-row control-group row-fluid fluid span6">
                        <div class="span2">
                            <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; ">Nro Pedido</label>
                        </div>
                        <div class="span6">
                            <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['codigo_pedido'])) ? $arrCabecera[0]['codigo_pedido'] : 'null';  ?>">
                        </div>
                    </div>
                </div>  
                <div id="list2">
                </div>                    
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data2 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    $(document).ready(function() {
         $('#list1').css( 'cursor', 'pointer' );
         $('#list2').css( 'cursor', 'pointer' );
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("historialpedido/get_detalle_historialpedido_list");?>/<?php echo (isset($id_pedido)) ? $id_pedido : 'null';  ?>/",
                        data: post_data, 
                        success: function(resultado) {
                            options.success(resultado);
                        }
                    });
                }
            },
            requestStart: function () { kendo.ui.progress($("#list1"), true); },
            requestEnd  : function () { kendo.ui.progress($("#list1"), false); },
            error: function(e) {
                alert('ERROR!');
            },
            pageSize: 3,
            schema: {
                model: {
                    id: "id_pedido",
                    fields: {
                        codigo_pedido: {type: "string"},
                        id_pedido: {type: "number"},
                        id_pedido_detalle: {type: "number"},
                        codigo_producto: {type: "string"},
                        nombre: {type: "string"},
                        marca: {type: "string"},
                        cantidad: {type: "number"},
                        precio: {type: "number"},
                        unidad: {type: "string"},
                        total: {type: "number"},
                    }
                }
            }
        });
        var dataSource2 = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("historialpedido/get_tracking_pedido_list");?>/<?php echo (isset($id_pedido)) ? $id_pedido : 'null';  ?>/",
                        data: post_data2, 
                        success: function(resultado) {
                            options.success(resultado);
                        }
                    });
                }
            },
            requestStart: function () { kendo.ui.progress($("#list2"), true); },
            requestEnd  : function () { kendo.ui.progress($("#list2"), false); },
            error: function(e) {
                alert('ERROR!');
            },
            pageSize: 3,
            schema: {
                model: {
                    id: "id_pedido",
                    fields: {
                        id_pedido: {type: "number"},
                        estado: {type: "string"},
                        fecha: {type: "string"}
                    }
                }
            }
        });

        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#list1").data("kendoGrid").hideColumn(1);
                $("#list1").data("kendoGrid").hideColumn(2);
                $("#list1").data("kendoGrid").hideColumn(3);
            }
            else {
                $("#list1").data("kendoGrid").showColumn(1);
                $("#list1").data("kendoGrid").showColumn(2);
                $("#list1").data("kendoGrid").showColumn(3);
            }
        }
        $(window).resize(function(){
            resizeGrid();
        });

        $("#list1").kendoGrid({
            dataSource: dataSource,
            selectable: "row",
            sortable: true,
            filterable: false,
            
            pageable: {
                refresh: true,
                pageSize: true
            },
            dataBound: resizeGrid,
            columns: [
                {field: "codigo_producto", width: 75, hide: true, title: "Codigo"},
                {field: "nombre", 
//                    headerAttributes: {class: "to_hide_phone"}, attributes:{class:"to_hide_phone "},
                    width: 320, 
                    title: "Producto"},
                {field: "marca", 
//                    headerAttributes: {class: "to_hide_phone"}, attributes:{class:"to_hide_phone "},
                    width: 75, 
                    title: "Marca"},
                {field: "unidad", 
//                    headerAttributes: {class: "to_hide_phone"}, attributes:{class:"to_hide_phone "},
                    width: 75,
                    title: "Unidad"},
                {field: "cantidad", width: 75, attributes:{style:"text-align:right"}, title: "Cantidad"},
                {field: "precio", width: 85, attributes:{style:"text-align:right"}, title: "Precio (S/.)"},
                {field: "total", width: 88, attributes:{style:"text-align:right"}, title: "Total (S/.)"}
            ]
        });

        $("#list2").kendoGrid({
            dataSource: dataSource2,
            selectable: "row",
            sortable: true,
            filterable: false,
            pageable: {
                refresh: true,
                pageSize: true
            },
            columns: [
//                {field: "codigo_producto", width: 80, title: "Codigo"},
                {field: "fecha", title: "Fecha"},
                {field: "estado", title: "Estado"}
            ]
        });

        <?php
            $URI = base_url('pedido/pedido_detalle/'.$id_categoria);
            if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'cliente') {
                echo "$.saveModal('#frmNew', '#submit', '#list1', '#msgForm', 'procesando...','".$URI."');";
            }
        ?>
    });
</script>