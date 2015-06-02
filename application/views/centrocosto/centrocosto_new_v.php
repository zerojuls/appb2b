<style>
        .modal-header {background-image: none; height: 50px;}                    
        @media (max-width: 480px) {.modal {left: 0}}
        //  @media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important}}
        .close {font-size: 40px}
        .box .content {padding: 0 15px;}
</style>

<div class="box color_light" >
    <div class="modal-header">
        <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
        <h4> <i class="icon-book"></i>
            <span>Nuevo Centro de Costo</span>
        </h4>
    </div>
    <div class="content">
        <form id="frmNew" action="" method="POST" class="form-horizontal row-fluid">
            <div id="list1">
            </div>
            <div class="form-actions row-fluid">
                <div class="span3 visible-desktop"></div>
                <div class="span7 ">
                    <button id="submit" class="btn btn-primary" type="submit">Guardar</button>
                    <button id="close" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <div id="msgForm"></div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var post_data = {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}
    $(document).ready(function() {
        $.saveModal('#frmNew', '#submit', '#list1', '#msgForm', "procesando...");
    });
</script>