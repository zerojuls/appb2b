<style>
    .modal {left: 40%;}
</style>
<div id="main_container2">
    <div class="row-fluid">
        <h1>Detalle de Producto </h1>
        <div class="form-row control-group row-fluid ">
            <div class="span2">
                <label for="normal-field" class="control-label">Codigo</label> 
            </div>
            <div class="span2 ">
                <input class="row-fluid" type="text" disabled="" value="<?php echo $arrCabecera[0]["codigo"]; ?>" />
            </div>
            <div class="span2">
                <label for="normal-field" class="control-label">Producto</label>
            </div>
            <div class="span5 ">
                <input class="row-fluid" type="text" disabled=""  value="<?php echo $arrCabecera[0]["producto"]; ?>" />
            </div>
        </div>
        <div class="form-row row-fluid">
            <a id="btnBack" href="#" class="btn btn-secondary color_19">Atras</a>
            <a id="btnExportar2" href="#" class="btn btn-secondary color_14" data-toggle="modal">Exportar</a>

            <br>
            <br>
            <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 870px;">
                <!--                 Div donde cargarán los fomularios -->
            </div>
            <div class="row-fluid">
                <div class="box paint" style="background-color: #FFFFFF;">
                    <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                        <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Detalle Consumo por Articulo</span> </h4>
                    </div>
                    <div class="content top ">
                        <div id="list1" class="table table-striped table-condensed">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End .row-fluid --> 

</div>
<script type="text/javascript">
    var post_data4 = {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'};
    var tituloInformacion = "Global Store";
    var seleccione = "Seleccione un Registro";
    $(document).ready(function() {
        $('#btnBack').on('click', (function() {
            $("#main_container2").hide('slide', {direction: 'left'});
            $("#main_container").show("drop");
        }));
        $('#list1').css('cursor', 'pointer');

        var dataSource = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("reporteconsumo/pedido_ceco_detail"); ?>/<?php echo (isset($id_producto)) ? $id_producto : 'null'; ?>/",
                        data: post_data4,
                        success: function(resultado) {
                            options.success(resultado);
                        }
                    });
                }
            },
            requestStart: function() {
                kendo.ui.progress($("#list1"), true);
            },
            requestEnd: function() {
                kendo.ui.progress($("#list1"), false);
            },
            error: function(e) {
                alert('ERROR!');
            },
            pageSize: 3,
            schema: {
                model: {
                    id: "id_pedido",
                    fields: {
                        id_pedido: {type: "string"},
                        fecha: {type: "string"},
                        codigo_pedido: {type: "string"},
                        sucursal: {type: "string"},
                        producto: {type: "number"},
                        cantidad: {type: "number"}
                    }
                }
            }
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
            columns: [
                {field: "fecha", title: "Fecha"},
                {field: "codigo_pedido", title: "Pedido"},
                {field: "sucursal", title: "Sucursal"},
                {field: "cantidad", width: 75, attributes: {style: "text-align:right"}, title: "Cantidad"},
                {command: {name: "Exportar", text: "", imageClass: "k-icon k-i-search", click: showDetail}, title: " ", width: "35px"}
            ]
        });

        $('#list1 table tr').live('dblclick', function() {
            var grid = $("#list1").data("kendoGrid");
            if (grid.dataItem(grid.select()) !== undefined) {
                var id = grid.dataItem(grid.select()).id_pedido;
                var param = {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', id: id};
                $.openModal('#boton', 'reporteconsumo/detail_pedido/', '#dlgNew', param, "Procesando ...");
            } else {
                return jAlert(Seleccione, tituloInformacion);
            }
        });

        $("#btnExportar2").click(function(e) {
            var URL = "<?php echo base_url('reporteconsumo/export_excel');?>/<?php echo (isset($id_producto)) ? $id_producto : 'null'; ?>";
            window.open(URL);
            e.preventDefault();
        });
    });


    function showDetail(e) {
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        var id = dataItem.id_pedido;
        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', id: id};
        $.openModal('#boton', 'reporteconsumo/detail_pedido/', '#dlgNew', param, "Procesando ...");
    }
</script>