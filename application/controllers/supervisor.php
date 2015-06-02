<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');


class Supervisor extends CI_Controller {
    var $_arr_Sesion;
    public function __construct() {
        
         parent::__construct();
         
         $this->_arr_Sesion = $this->session->userdata('ses_usuario');
         
         $this->load->database();
         $this->load->helper('url');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('supervisor_m');
    }
    
    public function index() {
        $arrSesion = $this->session->userdata('ses_usuario');
        $arrSesion['controller'] = 'Supervisores';
        $arrSesion['mGroup'] = 'm_ceco';
        $arrSesion['mOption'] = 'm_ceco_02';
        $this->load->view('includes/header');
        $this->load->view('includes/menu',$arrSesion);
        $this->load->view('includes/panel',$arrSesion);        
        $this->load->view('supervisor/supervisor_list_v');
        $this->load->view('includes/footer');
    }
    
    public function detail_supervisor() {
        $data = array( 'id_supervisor' => $this->input->post('id') );
        $this->load->view('supervisor/supervisor_detail_v', $data);
    }
    
    public function get_supervisor_list(){
        $arrSesion = $this->_arr_Sesion;
        $result = $this->supervisor_m->get_supervisor_list($arrSesion["id_corporativo"]);	
        header("Content-type: application/json");
	echo Json_encode($result);
        
    }
    
    public function get_detalle_supervisor_list($data){
        // $arrSesion = $this->_arr_Sesion;
        $result = $this->supervisor_m->get_montos_asignados($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function new_supervisor() {
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        // $arrSesion['arrCeco'] = $this->supervisor_m->get_();
        $this->load->view('supervisor/supervisor_new_v');
    }
    
    public function edit_supervisor() {
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        // $arrSesion['arrCeco'] = $this->supervisor_m->get_();
        $this->load->view('supervisor/supervisor_edit_v');
    }

}