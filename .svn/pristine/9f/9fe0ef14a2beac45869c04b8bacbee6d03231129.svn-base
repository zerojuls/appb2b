<div id="main">
    <div class="container">
        <div class="header row-fluid">
            <div class="logo"><?php echo anchor('main','<span>'.$controller.'</span> <span class="icon"></span>'); ?></div>
            <div class="top_right">
                <ul class="nav nav_menu">
                    <li class="dropdown">
                        <a class="dropdown-toggle administrator" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#">
                            <div class="title">
                                <span class="name"><?php if (isset($nombre_sucursal)) echo $nombre_sucursal; else echo $nombre_corporativo; ?></span>
                                <span class="subtitle"><?php  if (($alias_tipo_usuario)=='admin'||($alias_tipo_usuario)=='super') echo $nombre_persona.' - '.$tipo_usuario; else echo $nombre_persona.' - '.$tipo_usuario; ?></span>
                            </div>
                            <span class="icon"><?php echo img('img/eckerd.gif'); ?></span>
                       </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><?php echo anchor('perfil','<i class="icon-user"></i> Perfil'); ?></li>
                            
                            <li><?php echo anchor('main/logout','<i class="icon-unlock"></i> Cerrar Sesion'); ?></li>
                            <li><?php echo anchor('ayuda','<i class="icon-flag"></i> Ayuda'); ?></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- End top-right --> 
        </div>