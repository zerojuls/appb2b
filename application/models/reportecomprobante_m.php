<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Reportecomprobante_m extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_reportecomprobante_list($arrUsuario,$arrFechaIni, $arrFechaFin){
        $ar_result = array();
        $sql = "SELECT CP.id_comprobante_pago AS id_comprobante_pago , DATE_FORMAT(CP.fecha_registro, '%d/%m/%Y') AS fecha, "
              ."CP.numero AS numero_comprobante, SUM(cantidad * precio) AS valor_venta, SUM(cantidad * precio * 0.18) AS igv_venta, SUM(cantidad * precio * 1.18) AS total_venta "
              ."FROM comprobante_pago CP "
              ."INNER JOIN comprobante_pago_detalle CPD ON CP.id_comprobante_pago = CPD.id_comprobante_pago  "
              ."INNER JOIN sucursal S ON CP.id_cliente = S.id_cliente"  
              ." WHERE 	S.id_sucursal IN (SELECT id_sucursal FROM sucursal_usuario WHERE id_usuario = ".$arrUsuario." )";
        if($arrFechaIni != 'SF' && $arrFechaFin != 'SF'){ $sql .= "AND  CP.fecha_registro > '".$arrFechaIni."' AND CP.fecha_registro < '".$arrFechaFin."' "; 
        }else{  if($arrFechaIni == 'SF' && $arrFechaFin != 'SF'){ $sql .= "AND CP.fecha_registro < '".$arrFechaFin."' ";
                }else{ if($arrFechaIni != 'SF' && $arrFechaFin == 'SF'){ $sql .= "AND CP.fecha_registro > '".$arrFechaIni."' "; } }
        }
        $sql .= " GROUP BY (CP.id_comprobante_pago)";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_comprobanteventa_detail($arrComprobante){
        $ar_result = array();
        $sql = "SELECT PR.codigo AS codigo_producto,PR.nombre AS producto,CPD.cantidad AS cantidad, CPD.precio AS precio, CPD.cantidad * CPD.precio AS total FROM comprobante_pago_detalle CPD
                INNER JOIN producto PR ON CPD.id_producto = PR.id_producto
                WHERE CPD.id_comprobante_pago = ".$arrComprobante;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_cabecera_comprobante($arrComprobante){
        $ar_result = array();
        $sql = "SELECT DATE_FORMAT(CPG.fecha_registro, '%d/%m/%Y') AS fecha, CPG.numero AS numero_comprobante, C.nombre AS razon_social, DATE_FORMAT(NOW(), '%d/%m/%Y %H:%i %p') AS fecha_reporte, SUM(CPD.cantidad*CPD.precio) AS total_comprobante
                FROM comprobante_pago CPG
                INNER JOIN comprobante_pago_detalle  CPD ON CPD.id_comprobante_pago = CPG.id_comprobante_pago
                INNER JOIN cliente C ON CPG.id_cliente = C.id_cliente
                WHERE CPG.id_comprobante_pago = ".$arrComprobante;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function table_reporte_comprobante($id_comprobante_pago){
        $ar_result = array();
        $sql = "SELECT 		PR.codigo AS codigo_producto,PR.nombre AS producto,CPD.cantidad AS cantidad, CPD.precio AS precio_venta_unitario, 
                                ROUND((CPD.precio*0.18),2) AS igv_unitario, (CPD.precio + ROUND((CPD.precio*0.18),2)) AS valor_venta,
                                (CPD.cantidad*CPD.precio) AS precio_neto, ROUND((CPD.cantidad*CPD.precio*0.18),2) AS igv_neto,
                                ((CPD.cantidad*CPD.precio) + ROUND((CPD.cantidad*CPD.precio*0.18),2)) AS precio_total, C.codigo_cliente AS codigo_sap, 
                                C.nombre AS centro_costo, LPAD(CONVERT(P.id_pedido, CHAR(50)) ,10,'0') AS codigo_pedido, G.numero AS numero_guia,
                                DATE_FORMAT(P.fecha_registro, '%d/%m/%Y %H:%i:%s') AS fecha_pedido
                FROM  		consolidado_detalle COD 
                INNER JOIN 	guia_detalle GD ON COD.id_guia = GD.id_guia AND COD.id_producto = GD.id_producto
                INNER JOIN 	comprobante_pago_detalle CPD ON COD.id_comprobante_pago_detalle = CPD.id_comprobante_pago_detalle
                INNER JOIN 	comprobante_pago CPG ON CPD.id_comprobante_pago = CPG.id_comprobante_pago
                INNER JOIN 	guia G ON GD.id_guia = G.id_guia
                INNER JOIN 	pedido_guia PG ON G.id_guia = PG.id_guia
                INNER JOIN 	pedido P ON P.id_pedido = PG.id_pedido
                INNER JOIN 	producto PR ON CPD.id_producto = PR.id_producto
                INNER JOIN 	cliente C ON CPG.id_Cliente = C.id_cliente
                WHERE 		CPD.id_comprobante_pago = ".$id_comprobante_pago."
                ORDER BY 	C.nombre ASC
                ; ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
}