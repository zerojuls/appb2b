<!DOCTYPE html>
<html class="no-js login" lang="en">
    <head>
        <meta charset="utf-8">
        <title>Global Store - B2B</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="b2b">
        <meta name="author" content="Cesar Mamani">
        <link rel="shortcut icon" href="<?php echo site_url() ?>css/images/favicon.ico">
         <link href="<?php echo site_url() ?>css/base.css" rel="stylesheet">
        <link href="<?php echo site_url() ?>css/twitter/responsive.css" rel="stylesheet">
        <style>
            #login {bottom: 10%; padding: 0 100px ;}
            form {margin: 0 -22px 20px 20%;}
            p {color: #FFFFFF; margin: 0 30px 10px;}
            .btn.btn-primary { margin-left: 16%;}
            @media (max-width: 480px) {
                form { margin: 0 0 20px;}
                #login { padding: 0 7px 12px !important; left: 10% !important;}
                #login img { max-width: 54%;}
                .input-login { width: 235px !important; }
                #login span.name { padding-left: 40px !important;}
            }
            #login span.name {font-size: 20px; padding-left: 54px; line-height: 53px; color: #C5CCC8;}
            .mrgn_lgn {margin-bottom: 50%;}
            .row-fluid .span7 {width: 48.3333%;}
            .hdr{ z-index: 9999;}
            #login img { box-shadow: none;}
            #login_page {
                background-image: url("<?php echo site_url() ?>img/fondo_login.png");
                height: 100%;
                left: 0;
                top: 0;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div class="header row-fluid">
            <div class="span10"></div>
            <div class="span2">
                <div class="top_left " >
                    <div class="btn-group inline hdr">
                        <a style="margin-right: 4px" href="#"><img src="<?php echo site_url() ?>img/logo_iso.png"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div id="login_page"> 
                <div id="login">
                    <div class="row-fluid fluid" >
                        <div class="row-fluid">
                            <img src="<?php echo site_url() ?>img/logo_gs.png" > 
                        </div>
                        <div class="row-fluid">
                            <div class="title">
                                <span class="name">Acceso al Sistema</span>
                            </div>
                            <div style="padding-top: 50px;" class="content ">
                                    <?php echo form_open( site_url('login/isValid') , array('id' => 'formlogin', 'class' => 'bs-docs-example form-horizontal')); ?>
                                    <div class="control-group row-fluid">
                                        <div class="controls span8 input-append input-login margin-login">
                                            <input type="text" tabindex="1" id="txtUsername" name="txtUsername" placeholder="Usuario" class="row-fluid" >
                                            <span class="add-on"><i class="icon-user"></i></span>
                                        </div>
                                    </div>
                                    <div class="control-group row-fluid">
                                        <div class="controls span8 input-append input-login margin-login">
                                            <input type="password" tabindex="2" id="txtPassword" name="txtPassword" placeholder="Contraseña" class="row-fluid">
                                            <span class="add-on"><i class="icon-lock"></i></span>
                                        </div>
                                    </div>
                                    <div class="control-group row-fluid">
                                        <div class="controls span8">
                                            <button type="submit" class="btn btn-primary color_0">Iniciar Sesión</button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="content xpading">
                            <?php if (isset($sMsgError)) echo $sMsgError; ?>
                        </div>
                    </div>
                    <div class="navbar navbar-fixed-bottom">
                        <div class="footer row-fluid to_hide_phone">
                            <p>Global Store - 2014<p>	
                        </div>
                     </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo site_url('js/jquery.min.js') ?>"></script> 
        <script src="" type='text/javascript'>
            $(window).load(function() {
                $('#txtUsername').focus();
            });
        </script>
    </body>
</html>
