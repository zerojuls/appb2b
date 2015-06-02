<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * Developert   :   
 * Create Date  :   2014.03.25
 * Description  :   Genera una arreglo para poblar los objetos select
 * 
 */
class Seguridad {

    public function __construct() {
        log_message('debug', "Security App Class Initialized");

        // Seteando el objeto super
        $this->CI = & get_instance();

        // Cargando configuracion de basedatos
        $this->CI->load->database();

        log_message('debug', "Security App Successfully Run");
    }

    public function sec_menu() {
        $arrSesion = $this->CI->session->userdata('ses_usuario');

        $store = 'CONFIGURACION_ACCESO_GET';
        
        $arrParam = array(
                            $arrSesion['profile'], 
                            'b2b', //aplicacion por defecto
                            null,
                            null,
                            'M' //solo menus
        );
        
        $arrData   = $this->CI->db->query_sp($store, $arrParam);
        $i = -1;
        foreach ($arrData as $value) {
            $arrResultado[++$i] = $value['FuncionId'];
        }
        //$arrCombo = array('m_dsk', 'm_mae', 'm_mae_01', 'm_mae_02');
        
        return $arrResultado;
    }
    
    public function sec_class($sMethod) {
        $bResultado = FALSE;
        $arrObjeto = explode('::', $sMethod);
        
        if (count($arrObjeto)===2){ 
            $arrSesion = $this->CI->session->userdata('ses_usuario');

            $store = 'CONFIGURACION_ACCESO_GET';

            $arrParam = array(
                                $arrSesion['profile'], 
                                'b2b', //aplicacion por defecto
                                $arrObjeto[0], //clase
                                $arrObjeto[1], //metodo
                                'C' //solo Clases
            );
            $arrData   = $this->CI->db->query_sp($store, $arrParam);
            
            if (!(count($arrData)===1)){
                $bResultado = TRUE;//redirect('error404', 'Location');
            }
        }
        
        return $bResultado;
    }

}

// ------------------------------------------------------------------------

