<?php if(!empty($mensaje)){ ?>
<div><strong><?php echo $mensaje ?></strong></div>
<?php }else{ ?>
<div class="row-fluid">
    <div class="span6" style="border-right: 1px solid #cccccc">
        <h2><strong class="color19">✔ Producto añadido correctamente!</strong></h2>
        <div class="row-fluid">
            <div class="span6"><?php echo img('img/galeria_productos/'.$codigo.'.png'); ?></div>
            <div class="span6">
                <div><?php echo $producto; ?></div>
                <div><strong>Cantidad:</strong> <?php echo $cantidad; ?></div>
                <div><strong>Total:</strong> S/. <?php echo $precio_total ?></div>
            </div>
        </div>
    </div>
    <div class="span6">
        <h2>Hay <?php echo $num_articulo; ?> artículo(s) en su carrito.</h2>
        <hr />
        <div><strong>Total Productos:</strong> S/. <?php echo $total_producto; ?></div>
        <div><strong>Total Envío:</strong> Envío gratuito!</div>
        <div><strong>Total:</strong> S/. <?php echo $total_producto; ?></div>
        <hr />
        <button class="btn color_10" type="button" data-dismiss="modal" aria-hidden="true">&NestedLessLess; Seguir Comprando</button>
        <a href="<?php echo base_url('pedido/pedido_detalle/'.$id_categoria); ?>" class="btn color_12">Confirmar Pedido &NestedGreaterGreater;</a>
    </div>
</div>
<?php } ?>



