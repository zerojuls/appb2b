<style>
    .modal {left: 47%; position: absolute;}
    /*****/
    .k-picker-wrap .k-icon {margin-top: -5px;}
    @media (max-width: 600px){.span3 {width: 33.3333% !important;} .k-autocomplete.k-state-default, .k-picker-wrap.k-state-default, .k-numeric-wrap.k-state-default, .k-dropdown-wrap.k-state-default {margin-left: -22px;} .modal{left: 0 !important;}}
    .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {min-width: 0;width: 30px;}
    .k-grid .k-button, .k-edit-form-container .k-button {margin: 0 -7px;}
    .k-grid-content{overflow-y: hidden;}
</style>
<div id="main_container">
    <div class="row-fluid">
        <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 820px;">
        </div>
        <br/>
        <br>
        <div class="box paint" style="background-color: #FFFFFF;">
            <div class="title" style="background: url('img/shadows/b10.png') repeat scroll 0 0 rgba(0, 0, 0, 0)">
                <h4> <i class="icon-bar-chart" style="color: #666666;"></i><span style="color: #666666;">Autorización de Pedidos</span> </h4>
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
    $(document).ready(function() {
        var TituloInformacion = "Global Store";
        var Seleccione = "Seleccione un Registro";
        var dataSource = new kendo.data.DataSource({
            transport: {
                read:  function(options){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url('autorizarpedidos/get_autorizarpedidos_list');?>",
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
                alert('Error en la transaccion.');
            },
            pageSize: 10,
            schema: {
                model: {
                    id: 'id_pedido',
                    fields: {
                        id_pedido:          { type: "number" },
                        fecha:              { type: "string" },
                        codigo_pedido:      { type: "string" },
                        cliente:            { type: "string" },
                        responsable:        { type: "string" },
                        email:              { type: "string" },
                        estado:             { type: "string" },

                    }
                }
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
            pageable: {
                refresh: true,
                pageSize: true
            },
            columns: [ 
                { field: "codigo_pedido", 
                    width: 105, 
                    title: "Nro pedido" },
                { field: "cliente", 
//                   width: 120, 
                    title: "Centro Costo" },
                { field: "responsable", 
                  // width: 420,
                  title:"Responsable" },
                { field: "email", 
                  width: 275, 
                  title: "Email" },
//                 { field: "estado", width: 110, title: "Estado" },
              { command: { name: "Exportar", text: "", imageClass: "k-icon k-i-search", click: showDetail }, title: " ", width: "35px" }
            ]
        });
        
        function showDetail(e) {
                    e.preventDefault();
                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    var id = dataItem.id_pedido;
                    console.log(id);          
                    var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
                        $.openModal('#btnDetail', 'autorizarpedidos/detail_pedido/', '#dlgNew', param, "Procesando ...");         
        }
                
        $('#tbData table tr').live('dblclick', function () { 
            var grid = $("#tbData").data("kendoGrid");
                    if ( grid.dataItem(grid.select()) !== undefined){
                        var id = grid.dataItem(grid.select()).id_pedido;
                        var param = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', id : id}
                        $.openModal('#btnDetail', 'autorizarpedidos/detail_pedido/', '#dlgNew', param, "Procesando ...");   
                    } else{
                        return jAlert(Seleccione,tituloInformacion);
                    }
         });
    });
</script>