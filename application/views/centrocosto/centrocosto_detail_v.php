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
            <span>Montos Asignados</span>
        </h4>
    </div>
    <div class="content">
        <form id="frmNew" action="" method="POST" class="form-horizontal row-fluid">
            <div id="list1">
            </div>
<!--            <div class="form-actions row-fluid">
                <div class="span3 visible-desktop"></div>
                <div class="span7 ">
                    <button id="submit" class="btn btn-primary" type="submit">Guardar</button>
                    <button id="close" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>-->
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
                        url: "<?php echo base_url("centrocosto/get_detalle_centrocosto_list");?>/<?php echo (isset($id_sucursal)) ? $id_sucursal : 'null';  ?>/",
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
                    id: "id_sucursal",
                    fields: {
                        id_sucursal: {type: "number"},
                        sucursal: {type: "string"},
                        categoria: {type: "string"},
                        monto_asignado: {type: "number"},
                        monto_disponible: {type: "number"}
                    }
                }
            }
        });

        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
//                $("#list1").data("kendoGrid").hideColumn(1);
//                $("#list1").data("kendoGrid").hideColumn(2);
            }
            else {
//                $("#list1").data("kendoGrid").showColumn(1);
//                $("#list1").data("kendoGrid").showColumn(2);
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
//                 {field: "sucursal",  width: 180, title: "Marca"},
                {field: "categoria", width: 120, title: "Categoria"},
                {field: "monto_asignado", width: 90, decimals: 2, step: 0.01, attributes:{style:"text-align:right"}, title: "Monto Asignado(S/.)"},
                {field: "monto_disponible", width: 90, decimals: 2, step: 0.01, attributes:{style:"text-align:right"}, title: "Monto Disponible (S/.)"}
            ]
        });
  
        $('#submit').on('click', (function() {
            $(dlgNew).modal('hide');
        }));
    });
</script>