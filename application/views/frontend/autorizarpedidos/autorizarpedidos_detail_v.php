<style>
        .modal-header {background-image: none; height: 50px;}                    
        @media (max-width: 480px) {.modal {left: 0}}
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
        <div class="row-fluid">
            <div class="form-row control-group row-fluid fluid span6">
                <div class="span2">
                    <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; ">Fecha</label>
                </div>
                <div class="span4">
                    <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['fecha'])) ? $arrCabecera[0]['fecha'] : 'null';  ?>">
                </div>
                <div class="span2">
                    <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; margin-top: 5px;">Nro Pedido</label>
                </div>
                <div class="span4">
                    <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['codigo_pedido'])) ? $arrCabecera[0]['codigo_pedido'] : 'null';  ?>">
                </div>
            </div>
        </div>
        <form id="frmNew" action="<?php echo base_url('autorizarpedidos/authorize_pedido') ?>" method="POST" class="form-horizontal row-fluid">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <div class="controls hide"><input type="text" class="hide" id="txt_id" name="txt_id" value="<?php if (isset($id_pedido)) echo $id_pedido ?>"></div>
            <div id="list1">
            </div>
            <div class="form-actions row-fluid">
                <div class="span3 visible-desktop"></div>
                <div class="span7 ">
                                                <?php
                           if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super') { 
                                echo '<button id="submit" class="btn btn-primary" type="submit">Autorizar</button>';
                            }
                            ?>
                    <button id="close" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <div id="msgForm"></div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    $(document).ready(function() {
        $('#list1').css( 'cursor', 'pointer' );
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("autorizarpedidos/get_detalle_autorizarpedidos_list");?>/<?php echo (isset($id_pedido)) ? $id_pedido : 'null';  ?>/",
                        data: post_data, 
                        success: function(resultado) {
                            options.success(resultado);
                            $('#submit').click(function(){$('#tbData').data("kendoGrid").dataSource.read();});
                        },
                        complete: function() {
                            $('#submit').click(function(){$('#tbData').data("kendoGrid").dataSource.read();});
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
                        id_pedido: {type: "number"},
                        id_pedido_detalle: {type: "number"},
                        codigo_producto: {type: "string"},
                        nombre: {type: "string"},
                        marca: {type: "string"},
                        cantidad: {type: "number"},
                        precio: {type: "number"},
                        unidad: {type: "string"},
                    }
                }
            }
        });

        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#list1").data("kendoGrid").hideColumn(1);
                $("#list1").data("kendoGrid").hideColumn(2);
            }
            else {
                $("#list1").data("kendoGrid").showColumn(1);
                $("#list1").data("kendoGrid").showColumn(2);
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
            dataBound: resizeGrid,
            pageable: {
                refresh: true,
                pageSize: true
            },
            columns: [
                {field: "nombre", width: 180, title: "Producto"},
                {field: "marca",  width: 80, title: "Marca"},
                {field: "unidad", width: 80, title: "Unidad"},
                {field: "cantidad", width: 80,  attributes:{style:"text-align:right"}, title: "Cantidad"},
                {field: "precio", width: 90, attributes:{style:"text-align:right"}, title: "Precio (S/.)"}
            ]
        });

        <?php
            if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super') {
                echo "$.saveModal('#frmNew', '#submit', '#list1', '#msgForm', 'procesando...');";
            }
        ?>       
        $('#submit').on('click', (function() {
            $(dlgNew).modal('hide');
        }));
    });
</script>