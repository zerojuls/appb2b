<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');


class Historialpedido extends CI_Controller {
    var $_arr_Sesion;
    public function __construct() {
        
         parent::__construct();
         $this->_arr_Sesion = $this->session->userdata('ses_usuario');
         $this->load->database();
         $this->load->helper('url');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('historialpedido_m');
    }
    
    public function index() {
        $arrSesion = $this->_arr_Sesion;
        $arrSesion['controller'] = '<span>Historial</span><span class="to_hide_phone">&nbsp;de Pedidos</span>';
        $arrSesion['mGroup'] = 'm_pedido';
        $arrSesion['mOption'] = 'm_pedido_03';
        $result     =  $this->historialpedido_m->get_centrocosto($arrSesion["id_usuario"]);
        $estado     =  $this->historialpedido_m->get_estado();
        $arrData = array(
                            'jsonCentrocosto'   => Json_encode($result),
                            'jsonEstado'        => Json_encode($estado)
                         );
        $fechas = array(
            'fecha_ini'     => 'SF',
            'fecha_fin'     => 'SF',
            'centro_costo'  => '-1',
            'estado'        => '-1'
                        );
        $this->session->set_userdata('fechas', $fechas);
        $this->load->view('includes/header');
        $this->load->view('includes/menu',$arrSesion);
        $this->load->view('includes/panel',$arrSesion);
        $this->load->view('frontend/historialpedido/historialpedido_list_v', $arrData);
        $this->load->view('includes/footer');
    }
    
    public function detail_pedido() {
        $arrSesion = $this->_arr_Sesion;
        $result = $this->historialpedido_m->get_cabecera_historialpedido_list($this->input->post('id'));
        $data = array(  'id_pedido' => $this->input->post('id'), 
                        'arrCabecera' => $result, 
                        'id_categoria' => $this->historialpedido_m->get_idcategoria_pedido($this->input->post('id')),  
                        'alias_tipo_usuario' => $arrSesion["alias_tipo_usuario"]
                     );
        $this->load->view('frontend/historialpedido/historialpedido_detail_v', $data);
    }
    
    public function get_historialpedido_list(){
        $arrSesion = $this->_arr_Sesion;
        $arrData = $this->session->userdata('fechas');
        $result = $this->historialpedido_m->get_historialpedido_list($arrSesion["id_usuario"], $arrData['fecha_ini'], $arrData['fecha_fin'], $arrData['centro_costo'], $arrData['estado']);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function get_detalle_historialpedido_list($data){
        // $arrSesion = $this->_arr_Sesion;
        $result = $this->historialpedido_m->get_detalle_historialpedido_list($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function get_tracking_pedido_list($data){
        // $arrSesion = $this->_arr_Sesion;
        $result = $this->historialpedido_m->get_tracking_pedido_list($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function update_fechas($f_ini,$f_fin, $ceco, $est){
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        $fechas = array(
            'fecha_ini'     => $f_ini,
            'fecha_fin'     => $f_fin,
            'centro_costo'  => $ceco,
            'estado'        => $est
        );
        $this->session->set_userdata('fechas', $fechas);

    }
    
    public function repeat_pedido() {
        $arrSesion = $this->_arr_Sesion;
        $data = array(
            'id_pedido' => $this->input->post('txt_id'),
        );
        $result = $this->historialpedido_m->repeat_pedido($data['id_pedido'],$arrSesion['id_sucursal']);
        echo $result;       
    }
}