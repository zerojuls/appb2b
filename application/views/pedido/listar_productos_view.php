<style>
    .soles{font-size: 18px;}
    .height_medium {height: 107px;}
    .cantidad{ font-size: 40px !important; margin-right: 80% !important;}
    .content.numbers h3 {font-size: 25px;}
    @media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important;}}
    .btn.btn-secondary {background-image: none;margin-top: -4px;margin-right: -12px;}
    .btn.btn-secondary:hover {background-image: none;}
    .btn-large {padding: 15px 5px;font-size: 22px;}
    .thumbnail.small {max-width: 85px;}
    [class*="span"] {padding-left: 0;}

</style>
<div class="row-fluid">
    <div class="span3">
        <div class=" box color_2 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Asignado</h3>
                    <div class="row-fluid description mb5"><?php echo $nombre_categoria; ?></div>
                </div>
                <div class="row-fluid span6 width4"> 
                    <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php echo $monto_asignado; ?></h3>
                </div> 
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="box color_3 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Disponible</h3>
                    <div class="description mb5"><?php echo $nombre_categoria; ?></div>
                </div>
                <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php echo($monto_asignado-$monto_acumulado); ?></h3>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class=" box color_17 height_medium paint_hover">
            <div class="content numbers" style="padding: 10px 5px;">
                <div class="row-fluid span6 width8">
                    <h3 class="value">Monto Consumido</h3>
                    <div class="description mb5"><?php echo $nombre_categoria; ?></div>
                </div>
                <h3 class="value" style="float: right"><span class="soles"> S/.</span><?php if(isset($monto_acumulado)) echo $monto_acumulado; else echo '0.00';?></h3>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class=" box color_1 height_medium paint_hover">
            <div class="row-fluid fluid content numbers">
                <a href="<?php echo base_url('pedido/pedido_detalle/'.$id_categoria); ?>" class="btn btn-secondary">
                <div class="span9">
                    <div class="row-fluid fluid">
                        <h3 class="value cantidad"><span class="soles"> S/.</span><span id="txt_cantidad_total"><?php if(isset($txt_cantidad)) echo $txt_cantidad; else echo '0.00';?></span></h3>
                    </div>
                    <span class="row-fluid fluid" style="font-size: 18px !important;">
                        <span id="cantidad_items"><?php if(isset($cantidad_items)) echo $cantidad_items; else echo '0';?></span> Articulos Comprados
                    </span>
                </div>
                <div class="span3"><h1><i class=" icon-shopping-cart"></i></h1></div>
                </a>
            </div>
        </div>
    </div>
</div>
<div id="main_container">
    <div id="dlgNew" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <!-- Div donde cargarán los fomularios -->
    </div>
    <div class="row-fluid">
        <div class="box color_light">
          <div class="title">
            <h4><span>Lista de Productos</span></h4>
          </div>
          <!-- End .title -->
          <div class="content top">
            <table id="datatable_example" class="responsive table table-striped table-bordered" style="width:100%;margin-bottom:0; ">
              <thead>
                <tr>
                  <th class="jv">Codigo</th>
                  <th>Producto</th>
                  <th>Marca</th>
                  <th>Unidad</th>
                  <th>Precio</th>
                </tr>
              </thead>
              <tbody>
                <!-- Begin List Products -->
                <?php foreach($lista_producto->result() as $fila){ ?>
                <tr ondblclick="setOrder(this.id)" id="<?php echo $fila->id_producto; ?>" class="btnDetail">
                  <td><?php echo $fila->codigo; ?></td>
                  <td><?php echo $fila->producto; ?></td>
                  <td><?php echo $fila->marca; ?></td>
                  <td><?php echo $fila->unidad; ?></td>
                  <td><?php echo $fila->precio; ?></td>
                </tr>
                <?php } ?>
                <!-- End List Products -->
              </tbody>
            </table>
          </div>
          <!-- End .content --> 
        </div>
        <!-- End box --> 
      </div>
      <!-- End .row-fluid -->
</div>
<!-- End #container -->
<script type="text/javascript">
var TituloInformacion = "Global Store";
function setOrder(idprod){
    params = { <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>', id : idprod};
    $.openModal(idprod, '<?php echo base_url('pedido/new_pedido'); ?>', '#dlgNew', params, "Procesando...");
}
$(document).ready(function() {
  $.ajax({
        type: "POST",
        dataType: "json",
        data: { <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>" },
        url: "<?php echo base_url('pedido/get_pedido_saldo'); ?>",
        success: function(resultado) {
            $('#txt_cantidad_total').html(resultado[0].monto_comprado);
            $('#cantidad_items').html(resultado[0].cantidad);
        }
    });
  $('#datatable_example').dataTable({
    "sDom": "<'row-fluid table_top_bar'<'span12'<'to_hide_phone' f>>>t<'row-fluid control-group full top' <'span4 to_hide_tablet'l><'span8 pagination'p>>",
    "aaSorting": [[ 1, "asc" ]],
    "bPaginate": true,
    "sPaginationType": "full_numbers",
    "bJQueryUI": false,
  });
  $.extend( $.fn.dataTableExt.oStdClasses, {"s": "dataTables_wrapper form-inline"} );
  $(".chzn-select, .dataTables_length select").chosen({disable_search_threshold: 10});
});
</script>