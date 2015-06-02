<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');


class Main extends CI_Controller {
    var $_arr_Sesion;
    public function __construct() {
        
         parent::__construct();
         
         $this->_arr_Sesion = $this->session->userdata('ses_usuario');
         
         $this->load->database();
         $this->load->helper('url');
         $this->load->helper('form');
         $this->load->library('form_validation');
         $this->load->model('main_m');
    }
    
    public function index() {
        $arrSesion = $this->_arr_Sesion;
        $arrSesion['controller'] = 'Inicio';
        $arrSesion['mGroup'] = 'm_inicio';
        $arrSesion['mOption'] = '';
        $this->load->view('includes/header');
        $this->load->view('includes/menu', $arrSesion);
        $this->load->view('includes/panel', $arrSesion);        
        $data = array(
            'id_usuario' => $arrSesion["id_usuario"],
            'alias_tipo_usuario' => $arrSesion["alias_tipo_usuario"]
        );
        
        $resultPedidoCategoria = $this->main_m->get_pedido_categoria($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);
        $resultSaldosDisponibles = $this->main_m->get_saldos_disponibles($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);
        $resultFechaCierre = $this->main_m->get_fecha_cierre($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);
        $resultGraphics = $this->main_m->get_historial_compras($data);
        $num_resultGraphics = count($resultGraphics);
        $resultGraphics2 = $this->main_m->get_productos_consumidos($data);
        $num_resultGraphics2 = count($resultGraphics2);
        $resultTable3 = $this->main_m->get_montos_asignados($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);
        $resultTable = $this->main_m->get_pedido_pendientes($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);
        $resultTable2 = $this->main_m->get_pedido_autorizados($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);
        $datos_generales = array(
            'FechaCierre'                    => $resultFechaCierre,
            'SaldosDisponibles'              => $resultSaldosDisponibles,
            'PedidoCategoria'                => $resultPedidoCategoria,
            'arrayGraphics'                  => $resultGraphics,
            'num_rg'                         => $num_resultGraphics,
            'arrayGraphics2'                 => $resultGraphics2,
            'num_rg2'                        => $num_resultGraphics2,
            'arrayTable3'                    => $resultTable3,
            'arrayTable'                     => $resultTable,
            'arrayTable2'                    => $resultTable2
        );
        $this->load->view('frontend/main/main_v', $datos_generales);
        $this->load->view('includes/footer');
    }
    
    function get_historial_compras(){
        $arrSesion = $this->_arr_Sesion;
        $arrData = array(
            'id_usuario' => $arrSesion["user"],
            'id_tipo_usuario' => $arrSesion["typeUser"]
        );
        $data = $this->base_m->get_listar_json("HISTORICO_MAIN_GET", $arrData);
        echo $data;
    }

    public function logout() {

        cerrar_sesion();
    }

}
