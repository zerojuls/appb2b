<style>
        @media (min-width: 600px){.footer2{display: none;}}
        @media (max-width: 600px){.footer2{display: inline;} .btn { border: thin solid #B0B0B0 !important; margin-left: 4px; width: 98%;}}
</style>
    </div>
    <div id="footer"><p> &copy; Global store 2014 </p><span class=""><a href="#"></a></span> </div>
        <div class="footer2" >
            <div class="row-fluid fluid" style="padding-left: 5%; color: #5C6169;"><div class="span2" > Usuario: </div><div class="span10" style="font-size: 1.8rem; color: #434343;"><?php  echo $nombre_persona; ?></div></div>
            <div class="row-fluid fluid" style="padding-left: 5%; color: #5C6169;"><div class="span2" > Cargo: </div><div class="span10" style="font-size: 1.8rem; color: #434343;"><?php  echo $tipo_usuario; ?></div></div>
            <br><br>
            <?php echo anchor('main/logout', 'Desconectarse', 'id="btnLogout" class="btn btn-large btn-block btn-inverse"'); ?>
            <br><br>
        </div>
    </div>
    <!-- General scripts --> 
    <script src="<?php echo base_url('js/jquery.js'); ?>" type="text/javascript"> </script> 
    <!--[if !IE]> -->
        <script src="<?php echo base_url('js/plugins/enquire.min.js'); ?>" type="text/javascript"></script> 
    <!-- <![endif]-->
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/jquery.sparkline.min.js'); ?>"></script> <!-- TOOGLE SHITCH ON/OFF -->
    <script language="javascript" type="text/javascript"  src="<?php echo base_url('js/plugins/excanvas.compiled.js'); ?>"></script>
    <!--[if lt IE 7]>
        <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
    <![endif]-->
    <!-- kendo javascripts -->
    <script language="javascript" type="text/javascript" src="<?php echo base_url('kui/js/kendo.web.min.js'); ?>"></script> 
    <!-- Modal Concept -->
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/avgrund.js'); ?>"></script> 
    <script src="<?php echo base_url('js/bootstrap.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('js/fileinput.jquery.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('js/jquery-ui-1.8.23.custom.min.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('js/jquery.touchdown.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('js/plugins/plugins.js'); ?>" type="text/javascript"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/jquery.uniform.min.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/jquery.tinyscrollbar.min.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jnavigate.jquery.min.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/jquery.touchSwipe.min.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/jquery.peity.min.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/chosen/chosen/chosen.jquery.min.js'); ?>"></script> 
    <!-- Flot charts --> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/flot/jquery.flot.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/flot/jquery.flot.resize.js'); ?>"></script> 
    <!-- Data tables plugin --> 
    <script type="text/javascript" language="javascript" src="<?php echo base_url('js/plugins/datatables/js/jquery.dataTables.js'); ?>"></script> 
    <script language="javascript" type="text/javascript" src="<?php echo base_url('js/plugins/bootstrap-datepicker.js'); ?>"></script> 
    <!-- Custom made scripts for this template -->
    <script src="<?php echo base_url('js/scripts.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('js/highcharts.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/modules/data.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/modules/drilldown.js'); ?>" type="text/javascript"></script>
</body>
</html>
