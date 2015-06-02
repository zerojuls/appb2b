<?php
/*
 * Desarrollador    : Cesar Mamani Dominguez
 * Fecha Creación   : 2014.04.15
 * 
 * Desarrollador    : [Desarrollador]
 * Fecha Edición    : [yyyy.mm.dd]
 * 
 * Descripción      : Historial de pedidos, se muestran los pedidos hechos dentro de la B2B, 
 *                    para una mejor busqueda se filtrara por rango de fechas y centro de costo.
 */
?>
<style>
     .k-picker-wrap .k-icon {margin-top: -5px;}
     .modal {left: 40%; position: absolute;}
     
     /*****/
     .k-picker-wrap .k-icon {margin-top: -5px;}
     @media (max-width: 600px){.span3 {width: 33.3333% !important;} .k-autocomplete.k-state-default, .k-picker-wrap.k-state-default, .k-numeric-wrap.k-state-default, .k-dropdown-wrap.k-state-default {margin-left: -22px;} .modal{left: 0 !important;}}
     .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {min-width: 0;width: 30px;}
     .k-grid .k-button, .k-edit-form-container .k-button {margin: 0 -7px;}
     .k-grid-content{overflow-y: hidden;}
</style>
<div id="main_container">
    <div class="row-fluid">
        <div class="form-row control-group row-fluid fluid">
            <div class="span2">
                <label for="normal-field" class="control-label">Fecha Inicio</label>
            </div>
            <div class="span4 ">
                <input  id="txt_fec_ini" name="txt_fec_ini" class="row-fluid" value="" />
            </div>
            <div class="span2">
                <label for="normal-field" class="control-label">Fecha Fin</label>
            </div>
            <div class="span4 ">
                <input  id="txt_fec_fin" name="txt_fec_fin" class="row-fluid"  value="" />
            </div>
        </div>
        <div class="form-row control-group row-fluid fluid">
            <div class="span2 ">
                <label for="normal-field" class="control-label">Centro de costo</label>
            </div>
            <div class="span4 " >
                <input id="cb_centrocosto" name="cb_centrocosto" class="row-fluid" <?php if (($alias_tipo_usuario) == 'cliente' ){ echo 'disabled=""';} ?> />                   
            </div> 
            <div class="span2 ">
                <label for="normal-field" class="control-label">Estado</label>
            </div>
            <div class="span4 " >
                <input id="cb_estado" name="cb_estado" class="row-fluid" />                   
            </div> 
        </div>
        <br><br>
        <a id="btnSearch" href="#" class="btn btn-secondary color_19" data-toggle="modal">Filtrar</a>
        <a id="btnLimpiar" href="#" class="btn btn-secondary color_14" data-toggle="modal">Limpiar Filtro</a>
        <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 870px;">
        </div>
        <br><br><br>
        <div class="box paint" style="background-color: #FFFFFF;">
            <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Historial de Pedidos</span> </h4>
            </div>
            <div class="content top ">
                <div id="tbData" class="table table-striped table-condensed">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data1 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var tituloInformacion = "Global Store";
    var seleccione = "Seleccione un Registro";
    
    $(document).ready(function() {
        
//        $('#txt_fec_ini').val('');
//        $('#txt_fec_fin').val('');
        $.populateComboBox('#cb_centrocosto', "Seleccione", 'centro_costo', 'id_centrocosto', <?php echo (isset($jsonCentrocosto)) ? $jsonCentrocosto : 'null' ?>, 1);
        var cb_centrocosto = $("#cb_centrocosto").data("kendoComboBox");
        $.populateComboBox('#cb_estado', "Seleccione", 'estado', 'id_estado', <?php echo (isset($jsonEstado)) ? $jsonEstado : 'null' ?>, 1);
        var cb_estado = $("#cb_estado").data("kendoComboBox");
        $('#txt_fec_ini').bind($.browser.msie ? 'click' : 'change', function(event) {
            setFecha();
        });
        $('#txt_fec_fin').bind($.browser.msie ? 'click' : 'change', function(event) {
            setFecha();
        });
        $('#cb_centrocosto').bind($.browser.msie ? 'click' : 'change', function(event) {
            setFecha();
        });
        $('#cb_estado').bind($.browser.msie ? 'click' : 'change', function(event) {
            setFecha();
        });
        
        function setFecha(){
            
             var URL = "<?php echo base_url('historialpedido/update_fechas');?>/";
             if( $("#txt_fec_ini").val() === "")
                 URL = URL + "SF" + "/";
             else
                 URL = URL + castFecha($("#txt_fec_ini").val()) + "/";
             if( $("#txt_fec_fin").val() === "")
                 URL = URL + "SF" + "/";
             else
                 URL = URL + castFecha($("#txt_fec_fin").val()) + "/";      
             if( cb_centrocosto.value() === "")
                 URL = URL + "-1" + "/";
             else
                 URL = URL + cb_centrocosto.value() + "/";      
             if( cb_estado.value() === "")
                 URL = URL + "-1" + "/";
             else
                 URL = URL + cb_estado.value() + "/";      
             $.ajax({
                 type: "POST",
                 dataType: "html",
                 url:URL,
                 data: post_data1,
                 beforeSend: function(){
                    //$("#btnVer").attr("disabled", "disabled");
                    // $("#btnVer").html("Procesando ...");
                     
                 },
                 success: function(resultado){
//                     console.log(resultado);
//                     if (resultado){
//                         $('#tbData').data("kendoGrid").dataSource.read();
//                         $('#tbData').data("kendoGrid").dataSource.page(1);
//                     } 
                 },
                 error: function(){
                     jAlert('Error!', tituloInformacion);
                 },
                 complete: function(resultado){
//                     kendo.ui.progress($("#tbData"), false);
//                     if (resultado){
//                         $('#tbData').data("kendoGrid").dataSource.read();
//                         $('#tbData').data("kendoGrid").dataSource.page(1);                        
//                     }
                     console.log(resultado);
                 }
              });
        }
        
        function castFecha(fecha){
            if(fecha === '')
                return '';
            var arrayfecha = fecha.split('/');
                fecha = arrayfecha[0] + '-' + arrayfecha[1] + '-' + arrayfecha[2];
            return fecha;
        }
        
        $('#btnSearch').on('click', (function() {
            kendo.ui.progress($("#tbData"), true);           
            $('#tbData').data("kendoGrid").dataSource.read();
            $('#tbData').data("kendoGrid").dataSource.page(1);
            kendo.ui.progress($("#tbData"), false);
        }));
        
        function startChange() {
            var startDate = dpFechaI.value();

            if (startDate) {
                startDate = new Date(startDate);
                startDate.setDate(startDate.getDate() + 0);
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
                read:  function(options){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url('historialpedido/get_historialpedido_list');?>",
                        data: post_data, 
                        success: function (resultado){
                            options.success(resultado);
                        }
                    });
                }
            },
            requestStart: function () { kendo.ui.progress($("#tbData"), true); },
            requestEnd  : function () { kendo.ui.progress($("#tbData"), false); },
            error: function(e) {
                alert('Error en la transaccion');
            },
            complete:function() {kendo.ui.progress($("#tbData"), false);},
            pageSize: 5,
            schema: {
                model: {
                    id: 'id_pedido',
                    fields: {
                        id_pedido:          { type: "number" },
                        fecha:              { type: "string" },
                        codigo_pedido:      { type: "string" },
                        cliente:       { type: "string" },
                        monto:        { type: "number" },
                        estado:             { type: "string" }

                    }
                }
            }
        });
        
        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#tbData").data("kendoGrid").hideColumn(0);
                $("#tbData").data("kendoGrid").hideColumn(2);
            }
            else {
                $("#tbData").data("kendoGrid").showColumn(0);
                $("#tbData").data("kendoGrid").showColumn(2);
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
            pageable: {
                refresh: true,
                pageSize: true
            },
            columns: [ 
                { field: "fecha", 
                  // width: 190, 
                  title: "Fecha"
                },
                { field: "codigo_pedido", 
                  width: 105, 
                    title: "Nro pedido" 
                },
                { field: "cliente", 
               //      width: 140, 
                    title: "Centro Costo"
                },
                { field: "monto", 
                  width: 70, 
                    format  : "{0:n2}", culture: "de-DE", decimals: 2, step: 0.01,  
                    attributes:{style:"text-align:right;"}, title: "Monto (S/.)" 
                },
                { field: "estado", 
                    width: 106, 
                    title: "Estado" },
                { command: { name: "Exportar", text: "", imageClass: "k-icon k-i-search", click: showDetail }, title: " ", width: "35px" }
            ]
        });

        $('#btnLimpiar').click(function() {
                $('#cb_centrocosto').data("kendoComboBox").text('');
                $('#cb_estado').data("kendoComboBox").text('');
                $('#txt_fec_ini').val('');
                $('#txt_fec_fin').val('');
            });

        function showDetail(e) {
                    e.preventDefault();
                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    var id = dataItem.id_pedido;
                    console.log(id);          
                    var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
                        $.openModal('#btnDetail', 'historialpedido/detail_pedido/', '#dlgNew', param, "Procesando ...");         
        }
        
         $('#tbData table tr').live('dblclick', function () { 
            var grid = $("#tbData").data("kendoGrid");
                    if ( grid.dataItem(grid.select()) !== undefined){
                        var id = grid.dataItem(grid.select()).id_pedido;
                        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
                        $.openModal('#btnDetail', 'historialpedido/detail_pedido/', '#dlgNew', param, "Procesando ...");   
                    } else{
                        return jAlert(Seleccione,tituloInformacion);
                    }
         });
    });
    
</script>