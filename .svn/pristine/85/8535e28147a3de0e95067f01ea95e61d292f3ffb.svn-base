<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Reportecompras_m extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_reportecompras_list($arrParam) { //HISTORICO_COMPRAS_GET
        try {
            $arrResultado = $this->db->query_sp('HISTORICO_COMPRAS_GET',$arrParam);
            return $arrResultado;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado', 0, $e);
            return FALSE;
        }
    }


}