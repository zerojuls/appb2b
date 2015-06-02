<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Login_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function login($array) {
        //$array['password']  = sha1(md5($array['password']));
        $arrParam = array( $array['usuario'], $array['clave']);
        $consulta = $this->db->query_sp("USER_VALID", $arrParam);
        if (count($consulta) == 1) {
            $array = array();
            $array['nombre_persona']        = $consulta[0]["nombre_persona"];
            $array['tipo_usuario']          = $consulta[0]["tipo_usuario"];
            $array['alias_tipo_usuario']    = $consulta[0]["alias_tipo_usuario"];
            $array['id_usuario']            = $consulta[0]["id_usuario"];
            $array['nombre_usuario']        = $consulta[0]["nombre_usuario"];
            $array['id_sucursal']           = $consulta[0]["id_sucursal"];
            $array['nombre_sucursal']       = $consulta[0]["nombre_sucursal"];
            $array['id_corporativo']        = $consulta[0]["id_corporativo"];
            $array['nombre_corporativo']    = $consulta[0]["nombre_corporativo"];
            $array['imagen_corporativo']    = $consulta[0]["imagen_corporativo"];
        }
        $arrResultado = array( 'valido'     => count($consulta), 'infoUsuario'  => $array );
        return $arrResultado;
    }
}
