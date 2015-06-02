<h2>Solicitud de Pedido</h2>
<?php for($i=0;$i<count($rs);$i++){ ?>
<div class="span6">
    <div class="box color_2">
      <div class="content numbers">
        <h3><?php echo $rs[$i]['nombre']; ?></h3>
        <?php if($rs[$i]['id_categoria']==$idcategoria_pedido){ ?>
        <p align="right"><strong>Tiene un pedido pendiente por confirmar.</strong></p>
        <?php } ?>
        <p>Monto disponible: S/. <?php echo ($rs[$i]['monto_asignado']-$rs[$i]['monto_acumulado']); ?></p>
        <?php $fecha_cierre=($rs[$i]['fecha_cierre']>0)?$rs[$i]['fecha_cierre'].date('-m-Y'):'30'.date('-m-Y'); ?>
        <p>Fecha de cierre: <?php echo date('d/m/Y',strtotime($fecha_cierre)); ?></p>
        <p>Pedidos realizados: <?php echo $rs[$i]['pedidos_realizados']; ?> de <?php echo $rs[$i]['total_pedidos']; ?></p>
        <?php if(($rs[$i]['id_categoria']==$idcategoria_pedido || !$idcategoria_pedido) && strtotime($fecha_cierre)>=strtotime(date('d-m-Y')) && $rs[$i]['total_pedidos']>$rs[$i]['pedidos_realizados']){ ?>
        <div  align="right"><a href="<?php echo base_url('pedido/listar_productos/'.$rs[$i]['id_categoria']); ?>" class="btn" >Comprar</a></div>
        <?php } ?>
      </div>
    </div>
</div>
<?php } ?>