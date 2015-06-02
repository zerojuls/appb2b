<style>
    .modal {left: 40%;}
</style>
<div id="main_container2">
    <div class="row-fluid">
        <h1>Detalle de Producto </h1>
        <div class="form-row control-group row-fluid">
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
        <div class="form-row control-group row-fluid ">
            <div class="span2"><label for="normal-field" class="control-label">Id Sucursal</label></div>
            <div class="span2 "><input class="row-fluid" type="text" disabled="" value="<?php echo $id_sucursal; ?>" /></div>
            <!--<div class="span2"><label for="normal-field" class="control-label">Producto</label></div>-->
            <!--<div class="span5 "><input class="row-fluid" type="text" disabled=""  value="<? php echo $arrCabecera[0]["producto"]; ?>" /></div>-->
            
            <div class="span2 ">
                <label for="normal-field" class="control-label">Estado</label>
            </div>
            <div class="span4 " >
                <input id="cb_estado" name="cb_estado" class="row-fluid" />                   
            </div> 
        </div>
        <div class="form-row row-fluid">
            <a id="btnBack" href="#" class="btn btn-secondary color_14">Atras</a>
            <!--<a id="btnExportar2" href="#" class="btn btn-secondary color_14" data-toggle="modal">Exportar</a>-->

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
        $('#txt_fec_ini').val('');
        $('#txt_fec_fin').val('');
        $.populateComboBox('#cb_estado', "Seleccione", 'estado', 'id_estado', <?php echo (isset($jsonEstado)) ? $jsonEstado : 'null' ?>, 1);
        var cb_centrocosto = <?php echo (isset($id_sucursal)) ? $id_sucursal : 'null'; ?> ;
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
        $('#list1').css('cursor', 'pointer');
        function setFecha(){
            
             var URL = "<?php echo base_url('centrocosto/update_fechas');?>/";
             if( $("#txt_fec_ini").val() === "")
                 URL = URL + "SF" + "/";
             else
                 URL = URL + castFecha($("#txt_fec_ini").val()) + "/";
             if( $("#txt_fec_fin").val() === "")
                 URL = URL + "SF" + "/";
             else
                 URL = URL + castFecha($("#txt_fec_fin").val()) + "/";      
             URL = URL + cb_centrocosto + "/";      
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
                     kendo.ui.progress($("#tbData"), true);
                 },
                 success: function(resultado){
                     console.log(resultado);
                     if (resultado){
                         $('#tbData').data("kendoGrid").dataSource.read();
                         $('#tbData').data("kendoGrid").dataSource.page(1);
                     } 
                 },
                 error: function(){
                     jAlert('Error!', tituloInformacion);
                 },
                 complete: function(){
                     kendo.ui.progress($("#tbData"), false);
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
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url("centrocosto/get_historialpedido_list"); ?>/<?php echo (isset($id_sucursal)) ? $id_sucursal : 'null'; ?>/",
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
                {field: "codigo_pedido", width: 105, title: "Pedido"},
                { field: "monto", width: 70, format  : "{0:n2}", culture: "de-DE", decimals: 2, step: 0.01, attributes:{style:"text-align:right;"}, title: "Monto (S/.)"},
                { field: "estado", width: 106, title: "Estado" },
                { command: { name: "Exportar", text: "", imageClass: " icon-info-sign", click: showDetail }, title: " ", width: "35px" }
            ]
        });

        $('#list1 table tr').live('dblclick', function() {
            var grid = $("#list1").data("kendoGrid");
            if (grid.dataItem(grid.select()) !== undefined) {
                var id = grid.dataItem(grid.select()).id_pedido;
                var param = {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', id: id};
                $.openModal('#boton', 'centrocosto/detail_pedido/', '#dlgNew', param, "Procesando ...");
            } else {
                return jAlert(Seleccione, tituloInformacion);
            }
        });
    });


    function showDetail(e) {
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        var id = dataItem.id_pedido;
        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', id: id};
        $.openModal('#boton', 'centrocosto/detail_pedido/', '#dlgNew', param, "Procesando ...");
    }
</script>