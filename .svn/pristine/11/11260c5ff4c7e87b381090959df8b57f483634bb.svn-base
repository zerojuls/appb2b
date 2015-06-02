<?php ?>

<style>
    
.btn.btn-secondary {
     margin-right: 0;
 }
 
 #btnDetail{
     display: none;
 }
  @media (min-width: 1024px) {.row-fluid:not(.fluid) .span3 {width: 33%;}}
</style>

<div id="main_container">
    <div class="row-fluid">
        <h1>Carro de Compras <i class=" icon-shopping-cart"></i></h1>
        <a id="btnConfirm" href="#" class="btn btn-secondary color_19" data-toggle="modal">Confirmar</a>
        <a id="btnSave" href="#" class="btn btn-secondary color_19" data-toggle="modal">Guardar</a>
        <a id="btnBack" href="/b2b/pedido" class="btn btn-secondary color_14"  data-toggle="modal">Seguir comprando</a> 
        <br>
        <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <!-- Div donde cargarán los fomularios -->
        </div>
        <br>
        <div id="tbData">
        </div>
        <br>
        <rigth>
            <div id="tbSaldos" style="width: 350px; float: right;">
            </div>
        </rigth>
    </div>
    <!-- End .row-fluid --> 

</div>
<script type="text/javascript">
    $(document).ready(function() {
        var TituloInformacion = "Global Store";
        
        $('#btnConfirm').on('click', (function() {
            var param = null;
            $.openModal('#btnConfirm', 'pedido/confirm_pedido', '#dlgNew', param, "Procesando ...");
        }));
        
        $('#btnSave').click(function (e){
            e.preventDefault();
            var grid = $("#tbData").data("kendoGrid");
            // var row = grid.select();
            if ( grid.dataItem(grid.select()) !== undefined){
                var id = grid.dataItem(grid.select()).ID_PEDIDO_DETALLE;
                var pedido = grid.dataItem(grid.select()).ID_PEDIDO;
                var cant = grid.dataItem(grid.select()).CANTIDAD;
                var total = pedido*cant;
                // var param = {id : id};                
                console.log("ID: "+id);
                console.log("PEDIDO: "+pedido);
                console.log("CANTIDAD: "+cant);
                console.log("TOTAL: "+total);
                var URL="pedido/upd_pedido/"+id+"/"+pedido+"/"+cant;
                $.ajax({
                        type: "POST",
                        dataType: "html",
                        url: URL,
                        success: function (){
                        jAlert('Se guardo satisfactoriamente!', TituloInformacion);
                        },
                        error: function(){
                            jAlert('Surgió un error al guardar el pedido!', TituloInformacion);
                        },
                        complete: function(){
                            // $("#txt_cantidad").attr("disabled", "disabled");
                            $('#tbData').data("kendoGrid").dataSource.read();
                            $('#tbSaldos').data("kendoGrid").dataSource.read();
                        }
                });
            } else{
               return jAlert(Seleccione,TituloInformacion);
            }
        });
        // SALDOS TOTALES DE FACTURA
        var dataSaldos = new kendo.data.DataSource({
            transport: {
                read: {
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('pedido/get_pedido_saldo') ?>",
                    error: function() {
                        jAlert('Error al recibir datos de la tabla!', tituloInformacion);
                    }
                }
            },
            error: function(e) {
                alert('Error!');
            },
            pageSize: 3,
            schema: {
                model: {
                    fields: {
                        TITULO:     { type: "string" },
                        IMPORTE:    { type: "number" },
                    }
                }
            },
            aggregate: [{ field: "IMPORTE",  aggregate: "sum" }]
        });
        $("#tbSaldos").kendoGrid({
            dataSource: dataSaldos,
            selectable: "row",
            sortable: false,
            filterable: false,
            pageable: false,
            // scrollable:false,
            columns: [ 
                { field: "TITULO", width: 'auto', attributes:{style:"text-align:right"}, title: " ", footerTemplate: "<div style='text-align:right;'> Total:</div>"},
                { field: "IMPORTE", width: 'auto', format  : "{0:n2}", attributes:{style:"text-align:right"}, title: " ", footerTemplate: "<div style='float: right;'> #=kendo.toString(sum,'n2','es-PE')#"},
            ]
            
        });
        
        $('#tbData').css( 'cursor', 'pointer' );
        var dataSource = new kendo.data.DataSource({
            autoSync: true,
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?= base_url('pedido/get_detalle_pedido_list') ?>",
                        success: function(resultado) {
                            options.success(resultado);
                        }
                    });
                }
            },
            error: function(e) {
                alert('ERROR!');
            },
            batch: true,
            pageSize: 3,
            schema: {
                model: {
                    id: "ID_PEDIDO",
                    fields: {
                        ID_PEDIDO: {type: "number", editable: false, nullable: false},
                        ID_PEDIDO_DETALLE: {type: "number", editable: false, nullable: false},
                        CODIGO_PEDIDO: {type: "string", editable: false, nullable: false},
                        CODIGO_PRODUCTO: {type: "string", editable: false, nullable: false},
                        FECHA: {type: "string", editable: false, nullable: false},
                        NOMBRE_PRODUCTO: {type: "string", editable: false, nullable: false},
                        CANTIDAD: {
                            type: "number", 
                            editable: true, 
                            nullable: false,
//                            onChange: function()
//                            {
//                              console.log('change');
//                            },
                            template: '<input type="number" data-role="numerictextbox" id="txt_cantidad" name="txt_cantidad"  >'
                            },
                        PRECIO: {
                            type: "number", 
                            editable: false, 
                            nullable: false,
                            // template: '<input type="number" data-role="numerictextbox" id="txt_precio" name="txt_precio"  >'
                        },
                        UNIDAD: {type: "string", editable: false, nullable: false},
                        PRECIO_COMPRA: {
                            type: "number", 
                            editable: false, 
                            nullable: false,
                            // template: '<input type="number" data-role="numerictextbox" id="txt_total" name="txt_total"  >'
                            }
                    }
                }
            }
        });
        $("#tbData").kendoGrid({
            dataSource: dataSource,
            selectable: "single",
            sortable: false,
            filterable: false,
            pageable: false,
            columns: [
//                { field: "CODIGO_PEDIDO", title: "Código Pedido" },
//                { field: "CODIGO_PRODUCTO", title: "Código Producto" },
//                { field: "FECHA", title: "Fecha" },
                {field: "NOMBRE_PRODUCTO", title: "Producto"},
                {field: "CANTIDAD", width: 85, format: "{0:n0}", attributes:{style:"text-align:right"}, title: "Cantidad"},
                {field: "PRECIO", width: 70, format  : "{0:n2}", attributes:{style:"text-align:right"}, title: "Precio"},
                // {field: "UNIDAD", title: "Unidad"},
                {field: "PRECIO_COMPRA", width: 70, format  : "{0:n2}", attributes:{style:"text-align:right"}, title: "Total"}
            ],
            editable: true
        });
//        
//        function onChange() {
//                        console.log("Change :: " + this.value());
//                    }
//        function onSpin() {
//                        console.log("Spin :: " + this.value());
//                    }
        
        $("#txt_cantidad").kendoNumericTextBox({
            min: 0,
            format: 'n0',
//            change: onChange,
//            spin: onSpin
        });

    });
</script>