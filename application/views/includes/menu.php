<body>
    <div id="loading"><?php echo img('img/ajax-loader.gif'); ?></div>
    <div id="responsive_part">
        <div class="logo"><?php echo anchor('main','<span>'.$controller.'</span> <span class="icon"></span>'); ?></div>
        <ul class="nav responsive">
            <li>
                <button class="btn responsive_menu icon_item" data-toggle="collapse" data-target=".overview"> <i class="icon-reorder"></i></button>
            </li>
        </ul>
    </div>
    <!-- Responsive part -->
    <div id="sidebar" class="">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb">
                    <div class="end"></div>
                </div>
            </div>
        </div>
        <div class="viewport ">
            <div class="overview collapse">
                <div class="search row-fluid container">
                    <?php echo img('img/logo3.png'); ?>
                </div>
                <ul id="sidebar_menu" class="navbar nav nav-list container full">
                    <?php if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super' || ($alias_tipo_usuario) == 'cliente') {
                        ?>
                        <li class="accordion-group <?php if (isset($mGroup) && ($mGroup == 'm_inicio')) echo ' active ';?> color_18"> 
                            <?php echo anchor('main', img('img/menu_icons/dashboard.png') . '<span>Página Inicial</span>', array('class' => 'dashboard')); ?>
                        </li>
                    <?php
                    }
                    if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super') {
                        ?>
                        <li class="accordion-group <?php if (isset($mGroup) && ($mGroup == 'm_ceco')) echo ' active ';?> color_18"> 
                            <a class="accordion-toggle widgets collapsed" data-toggle="collapse" data-parent="#sidebar_menu" href="#collapse2">
                            <?php echo img('img/menu_icons/widgets.png'); ?><span>Centro de Costo</span>
                            </a>
                            <ul id="collapse2" class="accordion-body collapse <?php if (isset($mGroup) && ($mGroup == 'm_ceco')) echo ' in ';?>">
                                <li class="<?php if (isset($mOption) && ($mOption == 'm_ceco_01')) echo ' active ';?>"><?php echo anchor('centrocosto', 'Ver Sucursales'); ?></li>
                                <?php if (($alias_tipo_usuario) == 'admin') { ?>
                                    <li class="<?php if (isset($mOption) && ($mOption == 'm_ceco_02')) echo ' active ';?>"><?php echo anchor('supervisor', 'Ver Supervisores'); ?></li>
                                <?php  } ?>
                            </ul>
                        </li>
                        <?php  } if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super' || ($alias_tipo_usuario) == 'cliente') { ?>
                        <li class="accordion-group <?php if (isset($mGroup) && ($mGroup == 'm_pedido')) echo ' active ';?> color_18">
                            <a class="accordion-toggle widgets collapsed" data-toggle="collapse" data-parent="#sidebar_menu" href="#collapse3">
                            <?php echo img('img/menu_icons/forms.png'); ?><span>Pedidos</span>
                            </a>
                            <ul id="collapse3" class="accordion-body collapse <?php if (isset($mGroup) && ($mGroup == 'm_pedido')) echo ' in ';?>">
                                <?php if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'cliente') { ?>
                                    <li class="<?php if (isset($mOption) && ($mOption == 'm_pedido_01')) echo ' active ';?>"><?php echo anchor('pedido', 'Realizar pedido'); ?></li>
                                <?php } if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super' || ($alias_tipo_usuario) == 'cliente') { ?>
                                    <li class="<?php if (isset($mOption) && ($mOption == 'm_pedido_02')) echo ' active ';?>"><?php echo anchor('autorizarpedidos', 'Pedidos por autorizar'); ?></li>
                                <?php } if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super' || ($alias_tipo_usuario) == 'cliente') { ?>
                                    <li class="<?php if (isset($mOption) && ($mOption == 'm_pedido_03')) echo ' active ';?>"><?php echo anchor('historialpedido', 'Historial de pedidos'); ?></li>
                                </ul>
                            </li>
                            <?php }
                            } if (($alias_tipo_usuario) == 'admin' || ($alias_tipo_usuario) == 'super') { ?>
                        <li class="accordion-group <?php if (isset($mGroup) && ($mGroup == 'm_reporte')) echo ' active ';?> color_18">
                            <a class="accordion-toggle widgets collapsed" data-toggle="collapse" data-parent="#sidebar_menu" href="#collapse4">
                                <?php echo img('img/menu_icons/statistics.png'); ?><span>Reportes</span>
                            </a>
                            <ul id="collapse4" class="accordion-body collapse <?php if (isset($mGroup) && ($mGroup == 'm_reporte')) echo ' in ';?>">
                                <li class="<?php if (isset($mOption) && ($mOption == 'm_reporte_01')) echo ' active ';?>"><?php echo anchor('reportecompras', 'Histórico de compras corporativas'); ?></li>
                                <li class="<?php if (isset($mOption) && ($mOption == 'm_reporte_02')) echo ' active ';?>"><?php echo anchor('reportecomprobante', 'Reporte de comprobante de venta'); ?></li>
                                <li class="<?php if (isset($mOption) && ($mOption == 'm_reporte_03')) echo ' active ';?>"><?php echo anchor('reporteconsumo', 'Consumo por articulo y centro de costo'); ?></li>
                            </ul>
                        </li>
                        <?php } ?>
                </ul>
                <div class="menu_states row-fluid container ">
                    <div class="options pull-right">
                        <button id="menu_state_1" class="color_18" rel="tooltip" data-state ="sidebar_icons" data-placement="top" data-original-title="Menú Iconos">1</button>
                        <button id="menu_state_2" class="color_18 active" rel="tooltip" data-state ="sidebar_default" data-placement="top" data-original-title="Menú Fijo">2</button>
                        <button id="menu_state_3" class="color_18" rel="tooltip" data-placement="top" data-state ="sidebar_hover" data-original-title="Menú Deslizable">3</button>
                    </div>
                </div>
                <br>
                <h2 class="value white-color">Central Telefonica:</h2>
                <h1 class="white-color"><i class="icon-phone"></i> (01)618-0828</h1>
                <!-- End sidebar_box --> 
            </div>
        </div>
    </div>