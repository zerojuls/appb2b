<?php
/*
 * Desarrollador    : Cesar Mamani Dominguez
 * Fecha Creación   : 2014.04.10
 * 
 * Desarrollador    : [Desarrollador]
 * Fecha Edición    : [yyyy.mm.dd]
 * 
 * Descripción      : Reporte de Consumo por articulo y centro de costo, se ingresara o no un rango de fechas y se mostraran los resultados en una tabla hecha en kendo.
 */
?>
<style>
    .k-picker-wrap .k-icon {margin-top: -5px;}
    @media (max-width: 600px){.span3 {width: 33.3333% !important;} .k-autocomplete.k-state-default, .k-picker-wrap.k-state-default, .k-numeric-wrap.k-state-default, .k-dropdown-wrap.k-state-default {margin-left: -22px;} .modal{left: 0 !important;}}
    
    /*****/
     .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {min-width: 0;width: 30px;}
     .k-grid .k-button, .k-edit-form-container .k-button {margin: 0 1px;}
     .k-grid-content{overflow-y: hidden;}
</style>
<div class="row-fluid">
    <div id="main_container" >
    <div class="row-fluid fluid">
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
            <br>
            <br>
            <div class="row-fluid">
                <div class="box paint" style="background-color: #FFFFFF;">
                <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                    <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Reporte por Articulo y Centro de costo</span> </h4>
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
    <div id="tbDetalle" >
    </div>
</div>
<script type="text/javascript">

    var tituloInformacion = "Global Store";
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data1 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data2 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
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
                    url: "<?php echo base_url('reporteconsumo/get_reporteconsumo_list') ?>",
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
                    id: "id_producto",
                    fields: {
                        id_producto: {type: "number"},
                        codigo: {type: "string"},
                        producto: {type: "string"},
                        envase: {type: "string"},
                        unidad: {type: "string"},
                        cantidad: {type: "number"},
                        precio_venta: {type: "number"},
                        total: {type: "number"}
                    }
                }
            }
        });

        $('#btnVer').click(function(e) {
            e.preventDefault();
            var URL = "<?php echo base_url('reporteconsumo/load_reporte');?>";
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
                success: function(){
//                    $('#tbData').data("kendoGrid").dataSource.read();
//                    $('#btnVer').click(function() {
//                        $('#tbData').data("kendoGrid").dataSource.read();
//                    });
//                    $("#btnVer").removeAttr("disabled");
//                    $("#btnVer").html("Consultar");
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
                $("#tbData").data("kendoGrid").hideColumn(1);
                $("#tbData").data("kendoGrid").hideColumn(2);
                $("#tbData").data("kendoGrid").hideColumn(3);
            }
            else {
                $("#tbData").data("kendoGrid").showColumn(1);
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
                width: "auto",
                scrollable: true,
                reorderable: true,
                pageable: false,
                dataBound: resizeGrid,
                columns: [
                    {field: "codigo", width: 80, title: "Codigo" },
                    {field: "producto", width: 280, title: "Producto" },
                    {field: "envase", title: "Envase" },
                    {field: "unidad", title: "Unidad" },
                    {field: "cantidad", format  : "{0:n2}", decimals: 2, step: 0.01, headerAttributes: {style:"text-align:right;"}, attributes:{style:"text-align:right;"}, title: "Cant<span class='to_hide_phone'>idad</span>." },
                    {field: "precio_venta", format  : "{0:n2}", decimals: 2, step: 0.01, headerAttributes: {style:"text-align:right;"}, attributes:{style:"text-align:right;"}, title: "Precio <span class='to_hide_phone'>venta </span>(S/.)" },
                    {field: "total", format  : "{0:n2}", decimals: 2, step: 0.01, headerAttributes: {style:"text-align:right;"}, attributes:{style:"text-align:right;"}, title: "Total (S/.)" },
                    { command: [{ name: "Exportar", text: "", imageClass: "k-icon k-i-note", click: exportExcel }, { name: "Detalle", text: "", imageClass: "k-icon k-i-search", click: showDetail }], title: " ", width: "85px" }
                ]
            });
            

            
            $('#tbData table tr').live('dblclick', function () { 
            var grid = $("#tbData").data("kendoGrid");
            if(grid.dataItem(grid.select()) !== undefined) {
                var id = grid.dataItem(grid.select()).id_producto;
                $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: "reporteconsumo/detalle_producto/" + id,
                    data: post_data2,
                    beforeSend: function(){
                       $("#btnReq").attr("disabled", "disabled");
                       $("#btnReq").html("Procesando...");
                    },
                    success: function(resultado){
                        if (resultado){
                            $('#tbDetalle').html(resultado);
                            
                        } else {
                            jAlert ('El registro no existe',tituloInformacion);
                        }
                    },
                    error: function(){
                        jAlert('Error al cargar el registro',tituloInformacion);
                       $("#btnReq").removeAttr("disabled");
                       $("#btnReq").html("Atras");
                    },
                    complete: function(){
                        $("#btnReq").removeAttr("disabled");
                        $("#btnReq").html("Atras");
                        $( "#main_container" ).hide("slide", {direction: 'left'});
                        $( "#main_container2" ).show("slide", {direction: 'right'});
                    }
                });
            }else{
                jAlert("ERROR2",tituloInformacion);
            }
        });
        });

        $('#btnLimpiar').click(function() {
            $('#txt_fec_ini').val('');
            $('#txt_fec_fin').val('');
        });
        
        $("#btnExportar").click(function(e) {
            var URL = "<?php echo base_url('reporteconsumo/export_total');?>";
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
                    var id = dataItem.id_producto;
//                  wnd.content(detailsTemplate(dataItem));
//                  wnd.center().open();
                    console.log(id);  
                    $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: "reporteconsumo/detalle_producto/" + id,
                    data: post_data2,
                    beforeSend: function(){
                       $("#btnReq").attr("disabled", "disabled");
                       $("#btnReq").html("Procesando...");
                    },
                    success: function(resultado){
                        if (resultado){
                            $('#tbDetalle').html(resultado);
                            
                        } else {
                            jAlert ('El registro no existe',tituloInformacion);
                        }
                    },
                    error: function(){
                        jAlert('Error al cargar el registro',tituloInformacion);
                       $("#btnReq").removeAttr("disabled");
                       $("#btnReq").html("Atras");
                    },
                    complete: function(){
                        $("#btnReq").removeAttr("disabled");
                        $("#btnReq").html("Atras");
                        $( "#main_container" ).hide("slide", {direction: 'left'});
                        $( "#main_container2" ).show("slide", {direction: 'right'});
                    }
                });
            }
            
            function exportExcel(e) {
                    e.preventDefault();
                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    var id = dataItem.id_producto;
                    console.log(id);                    
                    var URL = "<?php echo base_url('reporteconsumo/export_excel');?>/" + id;
                     window.open(URL);               
        }
</script>

