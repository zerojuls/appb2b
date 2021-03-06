<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');


class Reportecompras extends CI_Controller {
    var $_arr_Sesion;
    public function __construct() {
        
         parent::__construct();
         //
         $this->_arr_Sesion = $this->session->userdata('ses_usuario');
         
         $this->load->database();
         $this->load->helper('url');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('reportecompras_m');
    }
    
    public function index() {
        $arrSesion = $this->session->userdata('ses_usuario');
        $arrSesion['controller'] = '<span class="to_hide_phone">Reporte Historico&nbsp;</span><span>Compras</span>';
        $arrSesion['mGroup'] = 'm_reporte';
        $arrSesion['mOption'] = 'm_reporte_01';
        $contador = 0;
        $arrParam = array(null);
        $sum[0] = 0;
        $sum[1] = 0;
        $sum[2] = 0;
        $sum[3] = 0;
        $sum[4] = 0;
        $sum[5] = 0;
        $sum[6] = 0;
        $sum[7] = 0;
        $sum[8] = 0;
        $sum[9] = 0;
        $sum[10] = 0;
        $sum[11] = 0;

        $series = array();
        $series[0]['name'] =  'enero';
        $series[1]['name'] =  'febrero';
        $series[2]['name'] =  'marzo';
        $series[3]['name'] =  'abril';
        $series[4]['name'] =  'mayo';
        $series[5]['name'] =  'junio';
        $series[6]['name'] =  'julio';
        $series[7]['name'] =  'agosto';
        $series[8]['name'] =  'septiembre';
        $series[9]['name'] =  'octubre';
        $series[10]['name'] =  'noviembre';
        $series[11]['name'] =  'diciembre';
        $series['drilldown'] = 'true';

        $query = $this->reportecompras_m->get_reportecompras_list($arrParam);	
        foreach($query as $r ) {
		$series[0]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes0'].']';
                $series[1]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes1'].']';
                $series[2]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes2'].']';
                $series[3]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes3'].']';
                $series[4]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes4'].']';
                $series[5]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes5'].']';
                $series[6]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes6'].']';
                $series[7]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes7'].']';
                $series[8]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes8'].']';
                $series[9]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes9'].']';
                $series[10]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes10'].']';
                $series[11]['data'][] = '["'.$r['centro_costo'].'",'.$r['mes11'].']';
                $sum[0] = $sum[0] + $r['mes0'];
                $sum[1] = $sum[1] + $r['mes1'];
                $sum[2] = $sum[2] + $r['mes2'];
                $sum[3] = $sum[3] + $r['mes3'];
                $sum[4] = $sum[4] + $r['mes4'];
                $sum[5] = $sum[5] + $r['mes5'];
                $sum[6] = $sum[6] + $r['mes6'];
                $sum[7] = $sum[7] + $r['mes7'];
                $sum[8] = $sum[8] + $r['mes8'];
                $sum[9] = $sum[9] + $r['mes9'];
                $sum[10] = $sum[10] + $r['mes10'];
                $sum[11] = $sum[11] + $r['mes11'];
        }   
       $result = array(
            'enero'     => $series[0],
            'febrero'   => $series[1],
            'marzo'     => $series[2],
            'abril'     => $series[3],
            'mayo'      => $series[4],
            'junio'     => $series[5],
            'julio'     => $series[6],
            'agosto'    => $series[7],
            'septiembre' => $series[8],
            'octubre'   => $series[9],
            'noviembre' => $series[10],
            'diciembre' => $series[11],
            'suma0'  => $sum[0],
            'suma1'  => $sum[1],
            'suma2'  => $sum[2],
            'suma3'  => $sum[3],
            'suma4'  => $sum[4],
            'suma5'  => $sum[5],
            'suma6'  => $sum[6],
            'suma7'  => $sum[7],
            'suma8'  => $sum[8],
            'suma9'  => $sum[9],
            'suma10'  => $sum[10],
            'suma11'  => $sum[11]
          );
       $jsonData= array(
               'jData' => Json_encode($result, JSON_NUMERIC_CHECK)
       );
        $this->load->view('includes/header');
        $this->load->view('includes/menu',$arrSesion);
        $this->load->view('includes/panel',$arrSesion);        
        $this->load->view('reportes/reportecompras_list_v',$jsonData);
        $this->load->view('includes/footer');
    }
    
}