<style>
     /*****/
     .k-picker-wrap .k-icon {margin-top: -5px;}
     @media (max-width: 600px){.span3 {width: 33.3333% !important;} .k-autocomplete.k-state-default, .k-picker-wrap.k-state-default, .k-numeric-wrap.k-state-default, .k-dropdown-wrap.k-state-default {margin-left: -22px;} }
     .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {min-width: 0;width: 30px;}
     .k-grid .k-button, .k-edit-form-container .k-button {margin: 0 -7px;}
     /*.k-grid-content{overflow-y: hidden;}*/
</style>
<div id="main_container">
    <div class="row-fluid">
<!--        <a id="btnNuevo" href="#" class="btn btn-secondary color_19" data-toggle="modal">Nuevo</a>
        <a id="btnEditar" href="#" class="btn btn-secondary color_19"  data-toggle="modal">Modificar</a> 
        <a id="btnEditar" href="#" class="btn btn-secondary color_14"  data-toggle="modal">Asignar Montos</a> -->
        <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        </div>
        <br/>
        <br>
        <div class="box paint" style="background-color: #FFFFFF;">
            <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Supervisores</span> </h4>
            </div>
            <div class="content top ">
                <div id="tbData" class="table table-striped table-condensed">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /**** Specific JS for this page ****/
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    var TituloInformacion = "Global Store";
    var Seleccione = "Seleccione un Registro";
    $(document).ready(function() {
//        $('#btnNuevo').on('click', (function() {
//            var param = post_data;
//            $.openModal('#btnNuevo', 'supervisor/new_supervisor', '#dlgNew', param, "Procesando...");
//       }));
//       $('#btnEdit').on('click', (function() {
//            var param = post_data;
//            $.openModal('#btnEditar', 'supervisor/edit_supervisor', '#dlgNew', param, "Procesando...");
//       }));
//       $('#btnAsignar').on('click', (function() {
//            var param = post_data;
//            $.openModal('#btnAsignar', 'supervisor/assign_supervisor', '#dlgNew', param, "Procesando...");
//       }));
       var dataSource = new kendo.data.DataSource({
            transport: {
                read:  function(options){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url('supervisor/get_supervisor_list');?>",
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
                    id: 'id_usuario',
                    fields: {
                        id_usuario:         { type: "number" },
                        nombre_supervisor:  { type: "string" },
                        tipo_documento:     { type: "string" },
                        documento:          { type: "string" },
                        email:              { type: "string" },
                        telefono:           { type: "string" },
                        corporativo:        { type: "string" },
                        estado:             { type: "string" }

                    }
                }
            }
        });
        
        function resizeGrid() {
            var width = $(window).width();
            if (width <= 600) {
                $("#tbData").data("kendoGrid").hideColumn(1);
                $("#tbData").data("kendoGrid").hideColumn(2);
                $("#tbData").data("kendoGrid").hideColumn(4);
                $("#tbData").data("kendoGrid").hideColumn(5);
            }
            else {
                $("#tbData").data("kendoGrid").showColumn(1);
                $("#tbData").data("kendoGrid").showColumn(2);
                $("#tbData").data("kendoGrid").showColumn(4);
                $("#tbData").data("kendoGrid").showColumn(5);
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
                { field: "nombre_supervisor", title: "Nombre" },
                { field: "tipo_documento", width: 130, title: "Tipo Documento" },
                { field: "documento", width: 95, title: "Documento" },
                { field: "email", title:"Email" },
                { field: "telefono", width: 100, title:"Telefono" },
                { field: "corporativo", title:"Corporativo" },
                { field: "estado", width: 90, title: "Estado" }
                // ,
                // { command: { name: "Exportar", text: "", imageClass: "k-icon k-i-search", click: showDetail }, title: " ", width: "35px" }
            ]
        });
        
//        function showDetail(e) {
//                    e.preventDefault();
//                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
//                    var id = dataItem.id_sucursal;
//                    console.log(id);          
//                    var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
//                        $.openModal('#btnDetail', 'supervisor/detail_supervisor/', '#dlgNew', param, "Procesando ...");        
//        }
//        
//         $('#tbData table tr').live('dblclick', function () { 
//            var grid = $("#tbData").data("kendoGrid");
//                    if ( grid.dataItem(grid.select()) !== undefined){
//                        var id = grid.dataItem(grid.select()).id_sucursal;
//                        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
//                        $.openModal('#btnDetail', 'supervisor/detail_supervisor/', '#dlgNew', param, "Procesando ...");   
//                        // return jAlert('Id: '+id,TituloInformacion);
//                    } else{
//                        return jAlert(Seleccione,TituloInformacion);
//                    }
//         });
    });
</script>