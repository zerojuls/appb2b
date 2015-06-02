<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Centrocosto_m extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_centrocosto_list($arrParam1,$arrParam2) {
             $ar_result = array();
        $consulta = "   SELECT          S.id_sucursal AS id_sucursal, 
					C.codigo_cliente AS codigo_cliente, 
					C.nombre AS nombre, 
					CONCAT(PE.nombre, ' ',PE.apellido) AS responsable,
					U.email AS email,
					C.direccion AS direccion,
					(CASE CU.estado WHEN 'A' THEN 'Activo' END) AS estado
			FROM 		sucursal_usuario SU
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	cliente C ON S.id_cliente = C.id_cliente
			INNER JOIN      cliente_usuario CU ON C.id_cliente = CU.id_cliente
			INNER JOIN      usuario U ON CU.id_usuario = U.id_usuario
			INNER JOIN      persona_usuario PU ON U.id_usuario = PU.id_usuario
			INNER JOIN      persona PE ON PU.id_persona = PE.id_persona
			WHERE 		SU.id_usuario = ".$arrParam1." AND U.id_tipo_usuario = 6 ;";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_montos_asignados($id_sucursal) {
        $ar_result = array();
        $consulta = "   SELECT     	S.id_sucursal AS id_sucursal, C.nombre AS sucursal, CA.nombre AS categoria,
                                        (CASE WHEN ((SELECT COUNT(*) FROM sucursal_categoria_monto SCM INNER JOIN sucursal_usuario SU ON SCM.id_sucursal = SU.id_sucursal 
                                        WHERE SU.id_sucursal = ".$id_sucursal." AND SCM.id_categoria = CC.id_categoria )>0) THEN SCM.valor
                                        ELSE CCM.valor END) AS monto_asignado,
                                        (CASE WHEN ((SELECT COUNT(*) FROM sucursal_categoria_monto SCM INNER JOIN sucursal_usuario SU ON SCM.id_sucursal = SU.id_sucursal 
                                        WHERE SU.id_sucursal = ".$id_sucursal." AND SCM.id_categoria = CC.id_categoria )>0) THEN (SCM.valor - SC.monto_acumulado)
                                        ELSE (CCM.valor - SC.monto_acumulado) END) AS monto_disponible
                        FROM       	monto M
                        INNER JOIN 	corporativo_categoria_monto CCM ON M.id_monto = CCM.id_monto
                        INNER JOIN 	corporativo_categoria CC ON CCM.id_corporativo_categoria = CC.id_corporativo_categoria
                        INNER JOIN 	sucursal_categoria_monto SCM ON M.id_monto = SCM.id_monto
                        INNER JOIN 	sucursal_categoria SC ON CCM.id_corporativo_categoria = SC.id_corporativo_categoria
                        INNER JOIN 	categoria CA ON CA.id_categoria = CC.id_categoria
                        INNER JOIN 	sucursal S ON SC.id_sucursal = S.id_sucursal
                        INNER JOIN 	cliente C ON S.id_cliente = C.id_cliente
                        INNER JOIN 	cliente_usuario CU ON C.id_cliente = CU.id_cliente
                        WHERE 		M.id_monto = 1
                        AND 		S.id_sucursal = ".$id_sucursal."; ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    /*NUEVA VENTANA DE HISTORIAL DE PEDIDO*/
    public function get_estado() {
        $ar_result = array();
        $consulta = " SELECT id_estado, nombre AS estado FROM estado WHERE id_estado IN ('S', 'D', 'L', 'E', 'P', 'N', 'Z') ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_historialpedido_list($id_usuario,$fecha_inicio,$fecha_fin,$id_sucursal,$estado) {
        $ar_result = array();
        $consulta = "   SELECT          LPAD(P.id_pedido,10,'0') AS codigo_pedido,
					P.id_pedido AS id_pedido,
					'active' AS activo, C.nombre AS cliente, CA.nombre AS categoria, 
					valor_venta AS monto, 
					LOWER(DATE_FORMAT(P.fecha_registro, '%d/%m/%Y %H:%i:%s %p')) AS fecha, 
					E.nombre AS estado
			FROM pedido P
			INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
			INNER JOIN unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
			INNER JOIN producto PR ON PD.id_producto = PR.id_producto
			INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
			INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
			INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
			INNER JOIN cliente C ON P.id_cliente = C.id_cliente
			INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
			INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
                        INNER JOIN estado E ON P.estado = E.id_estado
			WHERE SU.id_usuario = ".$id_usuario." ";
			if($id_sucursal!='-1'){$consulta .= "AND SU.id_sucursal = ".$id_sucursal." ";}
                        if($fecha_inicio!='SF' && $fecha_fin!='SF'){
                            $consulta .= "AND (P.fecha_registro BETWEEN  STR_TO_DATE(CONCAT('".$fecha_inicio."', '00:00:00'), '%Y-%m-%d %H:%i:%s') "
                                       . "AND  STR_TO_DATE(CONCAT('".$fecha_fin."', '23:59:59'), '%Y-%m-%d %H:%i:%s') )";
                         }else{
                            if($fecha_inicio=='SF' && $fecha_fin!='SF'){$consulta .= "AND  P.fecha_registro <= '".$fecha_fin."' ";}
                            else{
                              if($fecha_inicio!='SF' && $fecha_fin=='SF'){ $consulta .= "AND P.fecha_registro >= '".$fecha_inicio."' ";}
                           }
                         }
                         if($estado!='-1'){ $consulta .= "AND P.estado = '".$estado."' "; }
			$consulta .= "AND P.estado NOT IN ('T')
			GROUP BY PD.id_pedido
			ORDER BY P.fecha_registro DESC ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    /* MODAL DEL PEDIDO */
    public function detalle_pedido_list($arrParam) {
        $ar_result = array();
        $consulta = "   SELECT 		LPAD(P.id_pedido,10,'0') AS codigo_pedido,
                                        PD.id_pedido_detalle, PD.id_pedido, PR.codigo AS codigo_producto, PR.nombre, 
					M.nombre AS marca, U.nombre AS unidad, PD.cantidad, PD.precio, (PD.cantidad*PD.precio) AS total
                        FROM 		pedido_detalle PD
                        INNER JOIN 	pedido P ON PD.id_pedido=P.id_pedido
                        INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
                        INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
                        INNER JOIN 	unidad U ON PD.id_unidad=U.id_unidad
                        INNER JOIN 	marca M ON PR.id_marca=M.id_marca
                        WHERE P.id_pedido = ".$arrParam."";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function cabecera_pedido_list($arrPedido) {
        $ar_result = array();
        $consulta = "   SELECT 		LPAD(P.id_pedido,10,'0') AS codigo_pedido, 
                                        DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%i:%s %p') AS fecha_pedido, 
					SUM(PD.cantidad*PD.precio) AS total_pedido, 
                                        (CASE P.estado WHEN 'S' THEN 'Pendiente' WHEN 'D' THEN 'Despachado' WHEN 'L' THEN 'Parcial' WHEN 'E' THEN 'Entregado' WHEN 'P' THEN 'Procesado' WHEN 'N' THEN 'Anulado' WHEN 'T' THEN 'Temporal' END) AS estado_pedido
                        FROM 		pedido_detalle PD
                        INNER JOIN 	pedido P ON PD.id_pedido=P.id_pedido
                        INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
                        INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
                        INNER JOIN 	unidad U ON PD.id_unidad=U.id_unidad
                        INNER JOIN 	marca M ON PR.id_marca=M.id_marca
                        WHERE P.id_pedido = ".$arrPedido."";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
}
