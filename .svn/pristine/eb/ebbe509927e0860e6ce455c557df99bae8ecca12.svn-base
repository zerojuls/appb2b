<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');


class Reporteconsumo extends CI_Controller {
    var $_arr_Sesion;
    public function __construct() {
        
         parent::__construct();
         //
         $this->_arr_Sesion = $this->session->userdata('ses_usuario');
         $this->load->database();
         $this->load->helper('url');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('reporteconsumo_m');
        //load our new PHPExcel library
        $this->load->library('excel');
        $this->load->library('PHPExcel/iofactory.php');
    }
    
    public function index() {
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        $arrSesion = $this->session->userdata('ses_usuario');
        $arrSesion['controller'] = '<span class="to_hide_phone">Reporte de&nbsp;</span><span>Consumo</span>';
        $arrSesion['mGroup'] = 'm_reporte';
        $arrSesion['mOption'] = 'm_reporte_03';
        $this->load->view('includes/header');
        $this->load->view('includes/menu',$arrSesion);
        $this->load->view('includes/panel',$arrSesion);        
        $arrRpt = array(
            'fInicio'        => '',
            'fFin'           => '',
         );   
        $this->session->set_userdata('arrayrpt', $arrRpt);
        $this->load->view('reporteconsumo/reporteconsumo_list_v',$arrRpt);
        $this->load->view('includes/footer');
    }
    
    public function load_reporte($fechaIni,$fechaFin){
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        $arrRpt = array(
            'fInicio'    => $fechaIni,
            'fFin'       => $fechaFin,
         );   
        $this->session->set_userdata('arrayrpt', $arrRpt);
    }
    
    public function get_reporteconsumo_list(){
        // if ($this->seguridad->sec_class(__METHOD__)) return;
        $arrSesion = $this->session->userdata('ses_usuario');
        $arrRpt = $this->session->userdata('arrayrpt');
        $arrUsuario  = $arrSesion["id_usuario"];
        $arrFechaIni = $arrRpt['fInicio'];
        $arrFechaFin = $arrRpt['fFin'];
        $result = $this->reporteconsumo_m->get_reporteconsumo_list($arrUsuario, $arrFechaIni, $arrFechaFin);
        echo $arrReporte = json_encode($result, JSON_NUMERIC_CHECK);
//         $this->session->unset_userdata('arrayrpt'); 
    }
    
    public function detalle_producto($id_producto){
        $head = $this->reporteconsumo_m->get_cabecera_consumo($id_producto);
        $data = array('id_producto' => $id_producto, 'arrCabecera' => $head);
        $this->load->view('reporteconsumo/reporteconsumo_detail_v', $data);
    }
    
    public function pedido_ceco_detail($data){
        $result = $this->reporteconsumo_m->pedido_ceco_detail($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
        public function detail_pedido() {
         $result = $this->reporteconsumo_m->cabecera_pedido_list($this->input->post('id'));
        $data = array('id_pedido' => $this->input->post('id'),
                      'arrCabecera' => $result
                );
        $this->load->view('reporteconsumo/pedido_detail_v', $data);
    }
    
    public function detalle_pedido_list($data){
        $result = $this->reporteconsumo_m->detalle_pedido_list($data);	
        header("Content-type: application/json");
	echo Json_encode($result);
    }
    
    public function export_excel($id_producto){
        $arrSesion = $this->session->userdata('ses_usuario');
        $head = $this->reporteconsumo_m->get_cabecera_consumo($id_producto);
        $body = $this->reporteconsumo_m->table_reporte_consumo($id_producto);
        $razon_social = $arrSesion["nombre_corporativo"];
        $producto = $head[0]["producto"];
        $codigo = $head[0]["codigo"];
        $unidad = $head[0]["unidad"];
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
//        $sum = 0;
        $objExcel->getProperties()->setCreator("GLOBAL STORE SAC")
                                  ->setTitle(utf8_encode("Reporte del detalle de Consumo por Art&iacute;culo y Centro de Costo"))
                                  ->setSubject(utf8_encode("Reporte del detalle de Consumo por Art&iacute;culo y Centro de Costo"))
                                  ->setDescription(utf8_encode("Reporte del detalle de Consumo por Art&iacute;culo y Centro de Costo"));
        
        $styleArray_cabecera = array('font' => array('name' => 'Calibri','bold' => true,'size' => '11'));
        $styleArray_centrado = array('font' => array('name' => 'Calibri','bold' => true,'size' => '11'),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
        $styleArray_th = array('font' => array('name' => 'Calibri','bold' => false,'size' => '11','color' => array('rgb' => 'FFFFFF')),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '608CBE')),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
        $styleArray_td = array('font' => array('name' => 'Calibri','bold' => false,'size' => '11'),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'C6D5E1')));
        $styleArray_td_pie = array('font' => array('name' => 'Calibri','bold' => true,'size' => '11'),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'C6D5E1')));
    
        $objExcel->getActiveSheet()->getCell('A1')->setValue('GLOBAL STORE SAC');
        $objExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A1:B1');

        $objExcel->getActiveSheet()->getCell('A2')->setValue('Fecha: '.date("d/m/Y h:i a"));
        $objExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A2:B2');

        $objExcel->getActiveSheet()->getCell('A4')->setValue(utf8_encode('Reporte del detalle de Consumo por Art&iacute;culo y Centro de Costo'));
        $objExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray_centrado, false);
        $objExcel->getActiveSheet()->mergeCells('A4:E4');

        $objExcel->getActiveSheet()->getCell('A6')->setValue($razon_social); // OBTENER
        $objExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A6:B6');

//        $objExcel->getActiveSheet()->getCell('A7')->setValue($numero_comprobante); // OBTENER
//        $objExcel->getActiveSheet()->getStyle('A7')->applyFromArray($styleArray_cabecera, false);
//        $objExcel->getActiveSheet()->mergeCells('A7:B7');

        $objExcel->getActiveSheet()->getCell('A8')->setValue('Producto: ');
        $objExcel->getActiveSheet()->getCell('B8')->setValue($producto); // OBTENER
        $objExcel->getActiveSheet()->getStyle('A8')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->getStyle('B8')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('B8:E8');
        
        $objExcel->getActiveSheet()->getCell('A9')->setValue('Codigo: ');
        $objExcel->getActiveSheet()->getCell('B9')->setValue($codigo); // OBTENER
        $objExcel->getActiveSheet()->getStyle('A9')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('B9:C9');
        
        $objExcel->getActiveSheet()->getCell('A10')->setValue('Medida: ');
        $objExcel->getActiveSheet()->getCell('B10')->setValue($unidad); // OBTENER
        $objExcel->getActiveSheet()->getStyle('A10')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->getStyle('B10')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('B10:C10');

        $col = 0;
	foreach ($body[0] as $field=>$value) {
            $objExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 12, $field);
            $objExcel->getActiveSheet()->getStyleByColumnAndRow($col, '12')->applyFromArray($styleArray_th, false);
	    $col++;
	}
        $row = 13;
            foreach ($body as $data) {
                $col = 0;
                foreach ($data as $field_val) {
                    $objExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field_val);
                    $objExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->applyFromArray($styleArray_td, false);
                    $col++;
                }
                $row++;
            }
        
        $nombre_archivo = 'reporte_detalle_consumo';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
        public function export_total(){
        
        $arrSesion = $this->session->userdata('ses_usuario');
        $arrRpt = $this->session->userdata('arrayrpt');
        $arrUsuario  = $arrSesion["id_usuario"];
        $nombre_sucursal = $arrSesion["nombre_sucursal"];
        $nombre_corporativo = $arrSesion["nombre_corporativo"];
        $arrFechaIni = $arrRpt['fInicio'];
        $arrFechaFin = $arrRpt['fFin'];
        $body = $this->reporteconsumo_m->get_reporteconsumo_list($arrUsuario,$arrFechaIni, $arrFechaFin);
        if (isset($nombre_sucursal))
            { $sucursal  =  $nombre_sucursal;} 
        else
            { $sucursal  =  $nombre_corporativo;}
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
//        $sum = 0;
        $objExcel->getProperties()->setCreator("GLOBAL STORE SAC")
                                  ->setTitle(utf8_encode("Reporte de Consumo por Art&iacute;culo y Centro de Costo"))
                                  ->setSubject(utf8_encode("Reporte de Consumo por Art&iacute;culo y Centro de Costo"))
                                  ->setDescription(utf8_encode("Reporte de Consumo por Art&iacute;culo y Centro de Costo"));
        
        $styleArray_cabecera = array('font' => array('name' => 'Calibri','bold' => true,'size' => '11'));
        $styleArray_centrado = array('font' => array('name' => 'Calibri','bold' => true,'size' => '11'),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
        $styleArray_th = array('font' => array('name' => 'Calibri','bold' => false,'size' => '11','color' => array('rgb' => 'FFFFFF')),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '608CBE')),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
        $styleArray_td = array('font' => array('name' => 'Calibri','bold' => false,'size' => '11'),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'C6D5E1')));
        $styleArray_td_pie = array('font' => array('name' => 'Calibri','bold' => true,'size' => '11'),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'C6D5E1')));
    
        $objExcel->getActiveSheet()->getCell('A1')->setValue('GLOBAL STORE SAC');
        $objExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A1:B1');

        $objExcel->getActiveSheet()->getCell('A2')->setValue('Fecha: '.date("d/m/Y h:i a"));
        $objExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A2:B2');

        $objExcel->getActiveSheet()->getCell('A4')->setValue(utf8_encode('Reporte de Consumo por Art&iacute;culo y Centro de Costo'));
        $objExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray_centrado, false);
        $objExcel->getActiveSheet()->mergeCells('A4:E4');

        $objExcel->getActiveSheet()->getCell('A6')->setValue($sucursal); // OBTENER
        $objExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A6:B6');
        if($arrFechaIni == 'SF'){$FechaIni = '';} else{$FechaIni = $arrFechaIni;}
        if($arrFechaFin == 'SF'){$FechaFin = '';} else{$FechaFin = $arrFechaFin;}
        $objExcel->getActiveSheet()->getCell('A7')->setValue('Del '.$FechaIni.' AL '.$FechaFin); 
        $objExcel->getActiveSheet()->getStyle('A7')->applyFromArray($styleArray_cabecera, false);
        $objExcel->getActiveSheet()->mergeCells('A7:D7');        
        // Agrego los titulos de la tabla
        $col = 0;
	foreach ($body[0] as $field=>$value) {
            $objExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 9, $field);
            $objExcel->getActiveSheet()->getStyleByColumnAndRow($col, '9')->applyFromArray($styleArray_th, false);
	    $col++;
	}
        //Agrega el contenido de la tabla
        $row = 10;
            foreach ($body as $data) {
                $col = 0;
                foreach ($data as $field_val) {
                    $objExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field_val);
                    $objExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->applyFromArray($styleArray_td, false);
                    $col++;
                }
                $row++;
            }
        
        $nombre_archivo = 'reporte_consumo_articulo_sucursal';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}