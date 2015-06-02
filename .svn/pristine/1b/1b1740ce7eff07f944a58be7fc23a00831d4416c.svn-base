<style>
        #dlgNew{width: 770px;}
        .modal-header {background-image: none; height: 50px;}            
        @media (max-width: 480px) {.modal {left: 0}}
        //  @media (min-width: 1024px) {.width8 {width: 100% !important;} .width4 {width: 100% !important}}
        .close {font-size: 40px}
        .k-textbox .k-icon {position: static; padding-bottom: 5px;padding-top: 5px;}
        .k-icon, .k-tool-icon, .k-grouping-dropclue, .k-drop-hint, .k-column-menu .k-sprite, .k-grid-mobile .k-resize-handle-inner:before, .k-grid-mobile .k-resize-handle-inner:after {
            background-image: none;
        }
        .k-numerictextbox .k-link {height: 10px;}
        .box {position: relative;}
        .k-numerictextbox .k-icon {height: 0;}
</style>

<div class="box color_light">
    <div class="modal-header " >
        <button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
        <h4> <i class="icon-book"></i>
            <span>Detalle de Producto</span> 
        </h4>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="span6"><img src="<?php echo base_url('img/galeria_productos/'); ?>/<?php if (isset($txt_c_producto)) echo $txt_c_producto ?>.png "></div>
            <div class="span6" style="border-left: 1px solid #DDDDDD !important; margin-top: -30px;">
                <?php
                $attributes = array('class' => 'form-horizontal row-fluid', 'id' => 'frmNew');
                echo form_open('pedido/add_pedido',$attributes);
                ?>
                    <br>
                    <div id="txt_d_producto" name="txt_d_producto" class=""><h2><?php if (isset($txt_d_producto)) echo $txt_d_producto ?></h2></div>
                    <div class="row-fluid fluid " style="margin-top: -15px">
                        <div class="controls hide"><input type="text" class="hide" id="txt_id_tipo_precio" name="txt_id_tipo_precio" value="<?php if (isset($txt_id_tipo_precio)) echo $txt_id_tipo_precio ?>"></div>
                        <div class="controls hide"><input type="text" class="hide" id="txt_id" name="txt_id" value="<?php if (isset($txt_id)) echo $txt_id ?>"></div>
                        <div class="controls hide"><input type="text" class="hide" id="txt_id_envase" name="txt_id_envase" value="<?php if (isset($txt_id_envase)) echo $txt_id_envase ?>"></div>
                        <div class="controls hide"><input type="text" class="hide" id="txt_id_unidad" name="txt_id_unidad" value="<?php if (isset($txt_id_unidad)) echo $txt_id_unidad ?>"></div>
                        <div class="span6">
                            <div class="row-fluid fluid modal-float">
                                <div class="span6" align="right"><h3>Codigo: </h3></div>
                                <div id="txt_c_producto" name="txt_c_producto" class="span6">
                                    <h3><b><?php if (isset($txt_c_producto)) echo $txt_c_producto ?></b></h3>
                                </div>
                            </div>
                            <div class="row-fluid fluid modal-float">
                                <div class="span6" align="right"><h3>Marca: </h3></div>
                                <div id="txt_d_marca" name="txt_d_marca" class="span6" ><h3><b><?php if (isset($txt_d_marca)) echo $txt_d_marca ?></b></h3></div>
                            </div>
                            <div id="txt_d_envase" name="txt_d_envase" class="controls hide" ><input type="text" class="hide" id="txt_d_envase" name="txt_d_envase" value="<?php if (isset($txt_d_envase)) echo $txt_d_envase ?>"></div>
                            <div class="row-fluid fluid modal-float">
                                <div class="span6" align="right"><h3>Unidad: </h3></div>
                                <div id="txt_d_unidad" name="txt_d_unidad" class="span6" ><h3><b><?php if (isset($txt_d_unidad)) echo $txt_d_unidad ?></b></h3></div>
                            </div>
                        </div>
                        <div class="span6">
                            <div id="txt_precio" name="txt_precio"><h1 class="full value green"><b><?php if (isset($txt_precio)) echo $txt_precio ?></b></h1></div>
                            <div class="form-row control-group row-fluid fluid">
                                <span class="row-fluid k-textbox">
                                    <input type="number" data-role="numerictextbox" id="txt_cantidad" class="row-fluid " name="txt_cantidad" value="1" style="border: 1px solid #CCCCCC;text-align: right;">
                                </span>
                            </div>
                        </div>
                    </div>
                    <button id="submit"  class="btn btn-info btn-large btn-block well pagination-centered paint color_19" type="submit">Añadir al carrito</button>
                    <h3>Descripcion:</h3>
                    <blockquote>
                        <p><?php if (isset($txt_d_descripcion)) echo $txt_d_descripcion ?>.</p>
                    </blockquote>
                    <div id="msgForm"></div>
                </form>  
            </div>
        </div>
    </div>
    <div class="modal-footer">
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {        
        $.saveModal('#frmNew', '#submit', '#tbData', '#msgForm', "Procesando...");
//        $(document).on('click', '#submit', function(){ 
//            $('#close').trigger('click');
//        });
        $("#txt_cantidad").kendoNumericTextBox({
            min: 0,
            format: 'n0'
        });
        
        $('#submit').on('click', (function() {
            $.ajax({
                type: "POST",
                dataType: "json",
                data: { <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>" },
                url: "<?php echo base_url('pedido/get_pedido_saldo'); ?>",
                success: function(resultado) {
                    $('#txt_cantidad_total').html(resultado[0].IMPORTE);
                }
            });
            $(dlgNew).modal('hide');
        }));
    });
</script>