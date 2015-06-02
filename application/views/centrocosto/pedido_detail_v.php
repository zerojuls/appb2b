<style>
        .modal-header {background-image: none; height: 50px;}                    
        @media (max-width: 480px) {.modal {left: 0}}
        //  @media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important}}
        .close {font-size: 40px}
        .box .content {padding: 0 15px;}
</style>

<div class="box color_light" >
    <div class="modal-header">
        <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
        <h4> <i class="icon-book"></i>
            <span>Detalle de Pedido </span>
        </h4>
    </div>
    <div class="content">
        <br>
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
                <div class="form-row control-group row-fluid fluid span5">
                    <div class="span2">
                        <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; margin-top: 5px;">Fecha</label>
                    </div>
                    <div class="span10">
                        <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['fecha_pedido'])) ? $arrCabecera[0]['fecha_pedido'] : 'null';  ?>">
                    </div>
                </div>
                <div class="form-row control-group row-fluid fluid span2">
                    <div class="span2">
                        <label class="control-label" for="normal-field" style="margin-left: -10px; text-align: left; margin-bottom: -3px; margin-top: 5px;">Estado</label>
                    </div>
                    <div class="span10">
                        <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['estado_pedido'])) ? $arrCabecera[0]['estado_pedido'] : 'null';  ?>">
                    </div>
                </div>
            </div>
        <form id="frmNew" action="" method="POST" class="form-horizontal row-fluid">
            
            <div id="list2">
            </div>
            <div id="msgForm"></div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    $(document).ready(function() {
         $('#list2').css( 'cursor', 'pointer' );
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("centrocosto/detalle_pedido_list");?>/<?php echo (isset($id_pedido)) ? $id_pedido : 'null';  ?>/",
                        data: post_data, 
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
        
        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#list2").data("kendoGrid").hideColumn(1);
                $("#list2").data("kendoGrid").hideColumn(2);
                $("#list2").data("kendoGrid").hideColumn(3);
            }
            else {
                $("#list2").data("kendoGrid").showColumn(1);
                $("#list2").data("kendoGrid").showColumn(2);
                $("#list2").data("kendoGrid").showColumn(3);
            }
        }
        $(window).resize(function(){
            resizeGrid();
        });
        
        $("#list2").kendoGrid({
            dataSource: dataSource,
            selectable: "row",
            sortable: true,
            filterable: false,
            dataBound: resizeGrid,
            pageable: {
                refresh: true,
                pageSize: true
            },
            columns: [
                {field: "codigo_producto", width: 75, title: "Codigo"},
                {field: "nombre", width: 320, title: "Producto"},
                {field: "marca", width: 75, title: "Marca"},
                {field: "unidad", width: 75,title: "Unidad"},
                {field: "cantidad", width: 75, attributes:{style:"text-align:right"}, title: "Cantidad"},
                {field: "precio", width: 85, attributes:{style:"text-align:right"}, title: "Precio (S/.)"},
                {field: "total", width: 85, attributes:{style:"text-align:right"}, title: "Total (S/.)"}
            ]
        });

    });
</script>