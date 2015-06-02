<?php
/*
 * Desarrollador    : Cesar Mamani Dominguez
 * Fecha Creación   : 2014.04.10
 * 
 * Desarrollador    : [Desarrollador]
 * Fecha Edición    : [yyyy.mm.dd]
 * 
 * Descripción      : Reporte Comprobante de venta, se ingresara o no un rango de fechas y se mostraran los resultados en una tabla hecha en kendo.
 */
?>
<style>
    .k-picker-wrap .k-icon {margin-top: -5px;}
    @media (max-width: 600px){.span3 {width: 33.3333% !important;} .k-autocomplete.k-state-default, .k-picker-wrap.k-state-default, .k-numeric-wrap.k-state-default, .k-dropdown-wrap.k-state-default {margin-left: -22px;} }
    /*.k-button-icontext .k-icon, .k-button-icontext .k-image {margin-right: 3.3rem;}*/
    .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {
        min-width: 0;
        width: 30px;
    }
    .k-grid .k-button, .k-edit-form-container .k-button {
        margin: 0 -7px;
    }
    /*.k-grid-content{overflow-y: hidden;}*/
    
    .k-grid-Detalle{margin-left: 10px !important;}
    
    .k-button-icontext {padding-right: 23px;}
    
</style>
<div id="main_container">
    <div class="row-fluid">
        <!-- <div class="form-row row-fluid">
            <label for="normal-field" class="control-label span2">Centro de costo</label>
            <div class="controls span3">
                <input id="cb_centrocosto" name="cb_centrocosto" class="row-fluid" />                   
            </div> 
        </div> -->
        <div class="form-row control-group row-fluid fluid">
            <div class="span2">
                <label for="normal-field" class="control-label">Fecha Inicio</label>
            </div>
            <div class="span3 ">
                <input  id="txt_fec_ini" name="txt_fec_ini" class="row-fluid" value="" />
            </div>
            <div class="span2">
                <label for="normal-field" class="control-label">Fecha Fin</label>
            </div>
            <div class="span3 ">
                <input  id="txt_fec_fin" name="txt_fec_fin" class="row-fluid"  value="" />
            </div>
        </div>
        <div class="form-row row-fluid">
            <a id="btnVer" href="#" class="btn btn-secondary color_19" data-toggle="modal">Consultar</a>
            <a id="btnLimpiar" href="#" class="btn btn-secondary color_19" data-toggle="modal">Limpiar</a>
            <a id="btnExportar" href="#" class="btn btn-secondary color_14" data-toggle="modal">Exportar</a>
            <!--<a id="btnImprimir" href="#" class="btn btn-secondary color_14" data-toggle="modal">Imprimir</a>-->
            <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 670px;">
            </div>
            <br>
            <br>
            <div class="row-fluid">
                <div class="box paint" style="background-color: #FFFFFF;">
                <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                    <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Reporte de Comprobantes de venta</span> </h4>
                </div>
                <div class="content top ">
                                    <div id="tbData" class="table table-striped table-condensed">
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    var tituloInformacion = "Global Store";
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data1 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data2 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var TituloInformacion = "Global Store";
    var Seleccione = "Seleccione un Registro";

    $(document).ready(function() {
        $('#txt_fec_ini').val('');
        $('#txt_fec_fin').val('');
        
        function startChange() {
            var startDate = dpFechaI.value();

            if (startDate) {
                startDate = new Date(startDate);
                startDate.setDate(startDate.getDate() + 1);
                dpFechaF.min(startDate);
            }
        }

        function endChange() {
            var endDate = dpFechaF.value();

            if (endDate) {
                endDate = new Date(endDate);
                endDate.setDate(endDate.getDate() - 1);
                dpFechaI.max(endDate);
            }
        }

        var dpFechaI = $("#txt_fec_ini").kendoDatePicker({
            change: startChange,
            format: "yyyy/MM/dd"
        }).data("kendoDatePicker");

        var dpFechaF = $("#txt_fec_fin").kendoDatePicker({
            change: endChange,
            format: "yyyy/MM/dd"
        }).data("kendoDatePicker");

        dpFechaI.max(dpFechaF.value());
        dpFechaF.min(dpFechaI.value());

        
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo base_url('reportecomprobante/get_reportecomprobante_list') ?>",
                    data: post_data1, 
                    error: function() {
                        jAlert('Error al recibir datos de la tabla.',
                                tituloInformacion);
                    }
                }
            },
            requestStart: function () { kendo.ui.progress($("#tbData"), true); },
            requestEnd  : function () { kendo.ui.progress($("#tbData"), false); },
            error: function() {
                alert('Error en la transaccion.');
            },
            pageSize: 0,
            schema: {
                model: {
                    id: "id_comprobante_pago",
                    fields: {
                        id_comprobante_pago: {type: "number"},
                        fecha: {type: "string"},
                        numero_comprobante: {type: "string"},
                        valor_venta: {type: "number"},
                        igv_venta: {type: "number"},
                        total_venta: {type: "number"}
                    }
                }
            },
            aggregate: [
                        { field: "numero_comprobante", aggregate: "count" },
                        { field: "valor_venta", aggregate: "sum" },
                        { field: "igv_venta", aggregate: "sum" },
                        { field: "total_venta", aggregate: "sum" }
                       ]
        });

        $('#btnVer').click(function(e) {
            e.preventDefault();
            // var cb_centrocosto = $('#cb_centrocosto').data("kendoComboBox");

            var URL = "<?php echo base_url('reportecomprobante/load_reporte');?>";

                // URL = URL + cb_centrocosto.value();
            if ($("#txt_fec_ini").val() !== '')
                URL = URL + "/" + castFecha($("#txt_fec_ini").val());
            else
                URL = URL + "/" + "SF";
            if ($("#txt_fec_fin").val() !== '')
                URL = URL + "/" + castFecha($("#txt_fec_fin").val());
            else
                URL = URL + "/" + "SF";

            $.ajax({
                type: "POST",
                dataType: "html",
                url: URL,
                data: post_data, 
                beforeSend: function() {
                    $("#btnVer").attr("disabled", "disabled");
                    $("#btnVer").html("Procesando ...");
                },
                error: function() {
                    jAlert('Error dentro de la grilla!', tituloInformacion);
                },
                complete: function() {
                    $('#btnVer').click(function() {
                        $('#tbData').data("kendoGrid").dataSource.read();
                    });
                    $("#btnVer").removeAttr("disabled");
                    $("#btnVer").html("Consultar");
                }
            });
            
            function resizeGrid() {
                var width = $(window).width();
                if (width <= 600) {
                    $("#tbData").data("kendoGrid").hideColumn(2);
                    $("#tbData").data("kendoGrid").hideColumn(3);
                }
                else {
                    $("#tbData").data("kendoGrid").showColumn(2);
                    $("#tbData").data("kendoGrid").showColumn(3);
                }
            }
            $(window).resize(function(){
                resizeGrid();
            });
            
            $("#tbData").kendoGrid({
                dataSource: dataSource,
                selectable: "row",
                sortable: true,
                filterable: false,
                dataBound: resizeGrid,
                scrollable: true,
                reorderable: true,
                pageable: false,
                columns: [
                    {field: "fecha", width: 110, title: "Fecha" },
                    {field: "numero_comprobante", footerTemplate: "Total facturas: #=count# ",title: "Nro. Compr<span class='to_hide_phone'>obante</span>.", width: 105},
                    {field: "valor_venta", format  : "{0:n2}", decimals: 2, step: 0.01, headerAttributes: {style:"text-align:right;"}, attributes:{style:"text-align:right;"}, footerTemplate: "<div style='float:right;'>Venta: #=kendo.toString(sum,'n2','es-PE')#", title: "Valor <span class='to_hide_phone'>venta </span>(S/.)", width: 90},
                    {field: "igv_venta", format  : "{0:n2}", decimals: 2, step: 0.01, headerAttributes: {style:"text-align:right;"}, attributes:{style:"text-align:right;"}, footerTemplate: "<div style='float:right;'>Igv Total: #=kendo.toString(sum,'n2','es-PE')#", title: "Igv <span class='to_hide_phone'>venta </span>(S/.)", width: 90},
                    {field: "total_venta", format  : "{0:n2}", decimals: 2, step: 0.01, headerAttributes: {style:"text-align:right;"}, attributes:{style:"text-align:right;"}, footerTemplate: "<div style='float:right;'>Total: #=kendo.toString(sum,'n2','es-PE')#", title: "Total <span class='to_hide_phone'>venta </span>(S/.)", width: 90},
                    { command: [{ name: "Exportar", text: "", imageClass: "k-icon k-i-note", click: exportExcel }, { name: "Detalle", text: "", imageClass: "k-icon k-i-search", click: showDetail }], title: " ", width: "80px" }
                ]
            });
        });
        
        
        
        $('#tbData table tr').live('dblclick', function () { 
            var grid = $("#tbData").data("kendoGrid");
                    if ( grid.dataItem(grid.select()) !== undefined){
                        var id = grid.dataItem(grid.select()).id_comprobante_pago;
                        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id};
                        $.openModal('#boton', 'reportecomprobante/detalle_comprobante/', '#dlgNew', param, "Procesando ...");   
                    } else{
                        return jAlert(Seleccione,TituloInformacion);
                    }
         });
        $('#btnLimpiar').click(function() {
            $('#txt_fec_ini').val('');
            $('#txt_fec_fin').val('');
        });
        
        $("#btnExportar").click(function(e) {
            var URL = "<?php echo base_url('reportecomprobante/export_total');?>";
            window.open(URL);  
            e.preventDefault();
        });

    });

    function castFecha(fecha) {
        if (fecha === '')
            return '';
        var arrayfecha = fecha.split('/');
        fecha = arrayfecha[0] + '-' + arrayfecha[1] + '-' + arrayfecha[2];
        return fecha;
    }
    
        function showDetail(e) {
                    e.preventDefault();
                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    var id = dataItem.id_comprobante_pago;
                    var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id};
                    $.openModal('#boton', 'reportecomprobante/detalle_comprobante/', '#dlgNew', param, "Procesando ...");   
        }
        
        function exportExcel(e) {
                    e.preventDefault();
                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    var id = dataItem.id_comprobante_pago;
                    console.log(id);                    
                    var URL = "<?php echo base_url('reportecomprobante/export_excel');?>/" + id;
                     window.open(URL);               
        }
</script>
