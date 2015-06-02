<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');


class Centrocosto extends CI_Controller {
    var $_arr_Sesion;
    public function __construct() {
        
         parent::__construct();
         
         $this->_arr_Sesion = $this->session->userdata('ses_usuario');
         
         $this->load->database();
         $this->load->helper('url');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('centrocosto_m');
    }
    
    public function index() {
        $arrSesion = $this->session->userdata('ses_usuario');
        $arrSesion['controller'] = 'Sucursales';
        $arrSesion['mGroup'] = 'm_ceco';
        $arrSesion['mOption'] = 'm_ceco_01';
        $this->load->view('includes/header');
        $this->load->view('includes/menu',$arrSesion);
        $this->load->view('includes/panel',$arrSesion);        
        $this->load->view('centrocosto/centrocosto_list_v');
        $this->load->view('includes/footer');
    }
    
    public function detail_centrocosto() {
        //$arrSesion = $this->_arr_Sesion;
//        $result = $this->historialpedido_m->get_cabecera_historialpedido_list($this->input->post('id'));
        $data = array( 'id_sucursal' => $this->input->post('id') );
        $this->load->view('centrocosto/centrocosto_detail_v', $data);
    }
    
    public function get_centrocosto_list(){
        $arrSesion = $this->_arr_Sesion;
        $result = $this->centrocosto_m->get_centrocosto_list($arrSesion["id_usuario"],$arrSesion["alias_tipo_usuario"]);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function get_detalle_centrocosto_list($data){
        // $arrSesion = $this->_arr_Sesion;
        $result = $this->centrocosto_m->get_montos_asignados($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function detalle_centrocosto_pedido($id_centrocosto){
        // $head = $this->centrocosto_m->get_cabecera_ceco_pedido($id_centrocosto);
        $centrocosto =  $id_centrocosto;
        $estado     =  $this->centrocosto_m->get_estado();
        // $arrData = array( 'jsonEstado'        => Json_encode($estado));
        $fechas = array(
            'fecha_ini'     => 'SF',
            'fecha_fin'     => 'SF',
            'centro_costo'  => $id_centrocosto,
            'estado'        => '-1'
                        );
        $this->session->set_userdata('fechas', $fechas);
        $data = array(
                        'id_sucursal' => $centrocosto, 
                     //    'arrCabecera' => $head, 
                        'jsonEstado' => Json_encode($estado)
                     );
        $this->load->view('centrocosto/centrocosto_pedido_detail_v', $data);
    }
    
    public function get_historialpedido_list($id_sucursal){
        $arrSesion = $this->_arr_Sesion;
        $arrData = $this->session->userdata('fechas');
        $result = $this->centrocosto_m->get_historialpedido_list($arrSesion["id_usuario"], $arrData['fecha_ini'], $arrData['fecha_fin'], $id_sucursal, $arrData['estado']);	
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
    
    public function detail_pedido() {
         $result = $this->centrocosto_m->cabecera_pedido_list($this->input->post('id'));
        $data = array('id_pedido' => $this->input->post('id'),
                      'arrCabecera' => $result
                );
        $this->load->view('centrocosto/pedido_detail_v', $data);
    }
    
    public function detalle_pedido_list($data){
        $result = $this->centrocosto_m->detalle_pedido_list($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function new_centrocosto() {
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        // $arrSesion['arrCeco'] = $this->centrocosto_m->get_();
        $this->load->view('centrocosto/centrocosto_new_v');
    }
    
    public function edit_centrocosto() {
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        // $arrSesion['arrCeco'] = $this->centrocosto_m->get_();
        $this->load->view('centrocosto/centrocosto_edit_v');
    }
    
    public function assign_centrocosto() {
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        // $arrSesion['arrCeco'] = $this->centrocosto_m->get_();
        $this->load->view('centrocosto/centrocosto_assign_v');
    }
    
    
}