<?php ?>
<style>
    .soles{font-size: 18px;}
    .height_medium {height: 107px;}
    .cantidad{ font-size: 50px !important; margin-right: 80% !important;}
    .content.numbers h3 {font-size: 25px;}
    @media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important;}}
    .btn.btn-secondary {background-image: none;margin-top: -4px;margin-right: -12px;}
    .btn.btn-secondary:hover {background-image: none;}
    .btn-large {padding: 15px 5px;font-size: 22px;}
    .thumbnail.small {max-width: 85px;}
    [class*="span"] {padding-left: 0;}

</style>
<style scoped>
    /*** k-grid toolbar ***/
    #grid .k-toolbar
    {
        min-height: 27px;
        padding: 1.3em;
    }
    .category-label
    {
        vertical-align: middle;
        padding-right: .5em;
    }
    #category
    {
        vertical-align: middle;
    }
    .toolbar {
        float: right;
    }
</style>

<div class="row-fluid">
    <div class="span3">
        <div class=" box color_2 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Asignado</h3>
                    <div class="row-fluid description mb5">Utiles de Oficina</div>
                </div>
                <div class="row-fluid span6 width4"> 
                    <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php if (isset($txt_monto_asignado)) echo $txt_monto_asignado; else echo 'no data!';?></h3>
                </div> 
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="box color_3 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Consumido</h3>
                    <div class="description mb5">Utiles de Oficina</div>
                </div>
                <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php if (isset($txt_saldo)) echo $txt_saldo; else echo 'no data!'; ?></h3>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class=" box color_17 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Comprado</h3>
                    <div class="description mb5">Utiles de Oficina</div>
                </div>
                <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php if (isset($txt_monto_comprado)) echo $txt_monto_comprado; else echo 'no data!';?></h3>
            </div>
        </div>
    </div>
    <a id="btnDetail" href="#" class="btn btn-secondary span3" data-toggle="modal">
        <div class=" box color_1 height_medium paint_hover">
            <div class="row-fluid fluid content numbers">
                <div class="span9">
                    <div class="row-fluid fluid" id="txt_cantidad" name="txt_cantidad">
                        <h3 class="value cantidad"><?php if (isset($txt_cantidad)) echo $txt_cantidad; else echo 'no data!';?></h3>
                    </div>
                    <span class="row-fluid fluid" style="font-size: 18px !important;">
                        Articulos Comprados
                    </span>
                </div>
                <div class="span3"><h1><i class=" icon-shopping-cart"></i></h1></div>
            </div>
        </div>
    </a>
</div>

<div id="main_container">
    <!--        <a id="btnNew" href="#btnNew" class="btn btn-secondary color_19" data-toggle="modal">Nuevo</a>
            <a id="btnEdit" href="#btnEdit" class="btn btn-secondary color_19" data-toggle="modal">Modificar</a>
            <a id="btnAssign" href="#btnAssign" class="btn btn-secondary color_14"  data-toggle="modal">Asignar Monto</a> -->
    
    <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <!-- Div donde cargarán los fomularios -->
    </div>
    <div id="grid">
    </div>
</div>
<!-- End .row-fluid --> 
<!-- End #container --> 

<script type="text/javascript">
    /**** Specific JS for this page ****/
    $(document).ready(function() {
        $('#grid').css( 'cursor', 'pointer' );
        
        $('#btnDetail').on('click', (function() {
            $.ajax({
                type: "POST",
                dataType: "html",
                url: "pedido/detail_pedido",
                beforeSend: function(){
                    $("#btnDetail").attr("disabled", "disabled");
                    $("#btnDetail").html("Procesando ...");
                },
                success: function(resultado){
                    if (resultado){
                        $('#main_container').html(resultado);
                    } else {
                        jAlert ('El registro no existe',TituloInformacion);
                    }
                },
                error: function(){
                    jAlert('Error al cargar el registro',TituloInformacion);
                    $("#btnDetail").removeAttr("disabled");
                    $("#btnDetail").html("Detalle??");
                },
                complete: function(){
                    $("#btnDetail").removeAttr("disabled");
                    $("#btnDetail").html("Detalle?");
                }
            });
        }));
        
//        $('#btnDetail').on('click', (function() {
//            var param = null;
//            $.openModal('#btnDetail', 'pedido/detail_pedido', '#dlgNew', param, "Procesando ...");
//        }));

        var grid = new kendo.data.DataSource({
            transport: {
                read: function(options) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?= base_url('pedido/get_producto_list') ?>",
                        success: function(resultado) {
                            options.success(resultado);
                        }
                    });
                }
            },
            error: function(e) {
                alert('Error en la transaccion!!!');
            },
            pageSize: 0,
            schema: {
                model: {
                    id: 'CODIGO',
                    fields: {
                        CODIGO: {type: "string"},
                        ID_CATEGORIA: {type: "number"},
                        CATEGORIA: {type: "string"},
                        PRODUCTO: {type: "string"},
                        MARCA: {type: "string"},
                        UNIDAD: {type: "string"},
                        PRECIO: {type: "string"}
                    }
                }
            }
        });
        $("#grid").kendoGrid({
            dataSource: grid,
            selectable: "row",
            // toolbar: kendo.template($("#template").html()),
            sortable: true,
            filterable: false,
            change: function() {
                    var grid = $("#grid").data("kendoGrid");
                    // var rowCount = $('#grid tr').length;
                    if ( grid.dataItem(grid.select()) !== undefined){
                            var id = grid.dataItem(grid.select()).id_producto;
                            var param = {id : id};
                            $.openModal('#btnDetail', 'pedido/new_pedido', '#dlgNew', param, "Procesando...");
                    } else{
                        alert('Seleccione', 'Producto');
                    }
                },
            pageable: false,
            columns: [
                {field: "CODIGO", title: "Codigo"},
                {field: "ID_CATEGORIA", title: "Id Categoria"},
                {field: "CATEGORIA", title: "Categoria"},
                {field: "PRODUCTO", width: 260, title: "Producto"},
                {field: "MARCA", title: "Marca"},
                {field: "UNIDAD", title: "Unidad"},
                {field: "PRECIO", width: 120, title: "Precio"}
            ]
        });
    });
</script>