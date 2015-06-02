<style>
     /*****/
     .k-picker-wrap .k-icon {margin-top: -5px;}
     @media (max-width: 600px){.span3 {width: 33.3333% !important;} .k-autocomplete.k-state-default, .k-picker-wrap.k-state-default, .k-numeric-wrap.k-state-default, .k-dropdown-wrap.k-state-default {margin-left: -22px;} }
     .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {min-width: 0;width: 30px;}
     .k-grid .k-button, .k-edit-form-container .k-button {margin: 0 -7px;}
     .k-grid-content{overflow-y: hidden;}
</style>
<div class="row-fluid">
    <div id="main_container">
        <div class="row-fluid">
    <!--        <a id="btnNuevo" href="#" class="btn btn-secondary color_19" data-toggle="modal">Nuevo</a>
            <a id="btnEditar" href="#" class="btn btn-secondary color_19"  data-toggle="modal">Modificar</a> 
            <a id="btnEditar" href="#" class="btn btn-secondary color_14"  data-toggle="modal">Asignar Montos</a> -->
<!--            <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            </div>-->
            <br/>
            <br>
            <div class="box paint" style="background-color: #FFFFFF;">
                <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                    <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Sucursales</span> </h4>
                </div>
                <div class="content top ">
                    <div id="tbData" class="table table-striped table-condensed">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tbDetalle" >
    </div>
</div>
<script type="text/javascript">
    /**** Specific JS for this page ****/
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var post_data2 = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var TituloInformacion = "Global Store";
    var Seleccione = "Seleccione un Registro";
    $(document).ready(function() {
//        $('#btnNuevo').on('click', (function() {
//            var param = post_data;
//            $.openModal('#btnNuevo', 'centrocosto/new_centrocosto', '#dlgNew', param, "Procesando...");
//       }));
//       $('#btnEdit').on('click', (function() {
//            var param = post_data;
//            $.openModal('#btnEditar', 'centrocosto/edit_centrocosto', '#dlgNew', param, "Procesando...");
//       }));
//       $('#btnAsignar').on('click', (function() {
//            var param = post_data;
//            $.openModal('#btnAsignar', 'centrocosto/assign_centrocosto', '#dlgNew', param, "Procesando...");
//       }));
       var dataSource = new kendo.data.DataSource({
            transport: {
                read:  function(options){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url('centrocosto/get_centrocosto_list');?>",
                        data: post_data, 
                        success: function (resultado){
                            options.success(resultado);
                        }
                    });
                }
            },
            error: function(e) {
                alert('Error en la transaccion');
            },
            pageSize: 5,
            schema: {
                model: {
                    id: 'id_sucursal',
                    fields: {
                        id_sucursal:    { type: "number" },
                        codigo_cliente: { type: "string" },
                        nombre:         { type: "string" },
                        responsable:    { type: "string" },
                        email:          { type: "string" },
                        direccion:      { type: "string" },
                        estado:         { type: "string" }

                    }
                }
            }
        });
        
        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#tbData").data("kendoGrid").hideColumn(0);
                $("#tbData").data("kendoGrid").hideColumn(3);
                $("#tbData").data("kendoGrid").hideColumn(4);
            }
            else {
                $("#tbData").data("kendoGrid").showColumn(0);
                $("#tbData").data("kendoGrid").showColumn(3);
                $("#tbData").data("kendoGrid").showColumn(4);
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
                { field: "codigo_cliente", width: 110, title: "Codigo" },
                { field: "nombre", title: "Nombre" },
                { field: "responsable", width: 180, title: "Responsable" },
                { field: "email", title:"Email" },
                { field: "estado", width: 80, title: "Estado" },
                { command: { name: "Exportar", text: "", imageClass: " icon-chevron-right", click: showDetail }, title: " ", width: "35px" }
            ]
        });
        
//        function showDetail(e) {
//                    e.preventDefault();
//                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
//                    var id = dataItem.id_sucursal;
//                    console.log(id);          
//                    var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
//                        $.openModal('#btnDetail', 'centrocosto/detail_centrocosto/', '#dlgNew', param, "Procesando ...");        
//        }
        
        function showDetail(e) {
                    e.preventDefault();
                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    var id = dataItem.id_sucursal;
                    console.log(id);          
                     $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: "centrocosto/detalle_centrocosto_pedido/" + id,
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
       
        
         $('#tbData table tr').live('dblclick', function () { 
            var grid = $("#tbData").data("kendoGrid");
                    if ( grid.dataItem(grid.select()) !== undefined){
                        var id = grid.dataItem(grid.select()).id_sucursal;
                        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
                        $.openModal('#btnDetail', 'centrocosto/detail_centrocosto/', '#dlgNew', param, "Procesando ...");   
                        // return jAlert('Id: '+id,TituloInformacion);
                    } else{
                        return jAlert(Seleccione,TituloInformacion);
                    }
         });
    });
</script>