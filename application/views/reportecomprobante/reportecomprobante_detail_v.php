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
            <span>Detalle de Comprobante de Pago</span>
        </h4>
    </div>
    <div class="content">
        <br>
        <div class="row-fluid">
                <div class="form-row control-group row-fluid fluid span6">
                    <div class="span2">
                        <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; ">Fecha</label>
                    </div>
                    <div class="span4">
                        <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['fecha'])) ? $arrCabecera[0]['fecha'] : 'null';  ?>">
                    </div>
                    <div class="span2">
                        <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; margin-top: 5px;">Nro. Comprob.</label>
                    </div>
                    <div class="span4">
                        <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['numero_comprobante'])) ? $arrCabecera[0]['numero_comprobante'] : 'null';  ?>">
                    </div>
                </div>
                <div class="form-row control-group row-fluid fluid span6">
                    <div class="span2">
                        <label class="control-label" for="normal-field" style="text-align: left; margin-bottom: -3px; margin-top: 5px;">Razon Social</label>
                    </div>
                    <div class="span10">
                        <input class="row-fluid" type="text" disabled="" value="<?php echo (isset($arrCabecera[0]['razon_social'])) ? $arrCabecera[0]['razon_social'] : 'null';  ?>">
                    </div>
                </div>
                
            </div>
        <form id="frmNew" action="" method="POST" class="form-horizontal row-fluid">
            
            <div id="list1">
            </div>
            <div class="form-actions row-fluid">
                <div class="span3 visible-desktop"></div>
                <div class="span7 ">
                    <button id="export" class="btn btn-primary" type="button">Exportar</button>
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
        var id = <?php echo (isset($id_comprobante_pago)) ? $id_comprobante_pago : 'null';  ?>;
         $('#list1').css( 'cursor', 'pointer' );
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("reportecomprobante/get_comprobanteventa_detail");?>/<?php echo (isset($id_comprobante_pago)) ? $id_comprobante_pago : 'null';  ?>/",
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
                    // id: "id_comprobante_pago",
                    fields: {
                        codigo_producto: {type: "string"},
                        producto: {type: "string"},
                        cantidad: {type: "number"},
                        precio: {type: "number"},
                        total: {type: "number"},
                    }
                }
            }
        });
        
        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#list1").data("kendoGrid").hideColumn(1);
            }
            else {
                $("#list1").data("kendoGrid").showColumn(1);
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
                {field: "codigo_producto", width: 80, title: "Codigo"},
                {field: "producto", width: 200, title: "Producto"},
                {field: "cantidad", width: 75, attributes:{style:"text-align:right"}, title: "Cantidad"},
                {field: "precio", width: 90, attributes:{style:"text-align:right"}, title: "Precio (S/.)"},
                {field: "total", width: 90, attributes:{style:"text-align:right"}, title: "Total (S/.)"}
            ]
        });

        // $.saveModal('#frmNew', '#submit', '#list1', '#msgForm', "procesando...");
        $('#export').on('click', (function() {
            $(dlgNew).modal('hide');
            var URL = "<?php echo base_url('reportecomprobante/export_excel');?>/" + id;
            window.open(URL);  
        }));

    });
</script>