<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Historialpedido_m extends CI_Model {

    public function __construct() {
        parent::__construct();
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

    public function get_detalle_historialpedido_list($arrParam) {
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
                        WHERE           P.id_pedido = ".$arrParam."; ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_tracking_pedido_list($arrParam) {
        $ar_result = array();
        $consulta = "   SELECT T.id_pedido AS id_pedido, E.nombre AS estado, T.fecha_creacion AS fecha
                        FROM tracking T
                        INNER JOIN estado E ON T.id_estado = E.id_estado
                        WHERE id_pedido = ".$arrParam." 
                        ORDER BY T.fecha_creacion";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_cabecera_historialpedido_list($arrParam) {
        $ar_result = array();
        $consulta = "   SELECT 		LPAD(P.id_pedido,10,'0') AS codigo_pedido,
                                        DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%i:%s %p') AS fecha_pedido, 
					SUM(PD.cantidad*PD.precio) AS total_pedido, 
                                        E.nombre AS estado_pedido
                        FROM 		pedido_detalle PD
                        INNER JOIN 	pedido P ON PD.id_pedido=P.id_pedido
                        INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
                        INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
                        INNER JOIN 	unidad U ON PD.id_unidad=U.id_unidad
                        INNER JOIN 	marca M ON PR.id_marca=M.id_marca
                        INNER JOIN      estado E ON P.estado = E.id_estado
                        WHERE           P.id_pedido = ".$arrParam."; ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_centrocosto($arrParam) {
        $ar_result = array();
        $consulta = "   SELECT      S.id_sucursal  AS id_centrocosto, C.nombre AS centro_costo
                        FROM        sucursal S 
                        INNER JOIN  corporativo CO ON S.id_corporativo = CO.id_corporativo
                        INNER JOIN  cliente C ON S.id_cliente = C.id_cliente
                        WHERE       CO.id_cliente = (
                                                    SELECT DISTINCT(CO1.id_cliente) FROM sucursal_usuario SU
                                                    INNER JOIN sucursal SC ON SU.id_sucursal = SC.id_sucursal 
                                                    INNER JOIN corporativo CO1 ON SC.id_corporativo = CO1.id_corporativo
                                                    INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
                                                    WHERE SU.id_usuario = ".$arrParam." AND U.id_tipo_usuario NOT IN (6)
                                                    )   
                        AND         C.id_cliente NOT IN (
                                                            SELECT DISTINCT(CO1.id_cliente) FROM sucursal_usuario SU
                                                            INNER JOIN sucursal SC ON SU.id_sucursal = SC.id_sucursal 
                                                            INNER JOIN corporativo CO1 ON SC.id_corporativo = CO1.id_corporativo
                                                            INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
                                                            WHERE SU.id_usuario = ".$arrParam." AND U.id_tipo_usuario NOT IN (6)
                                                        )";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_estado() {
        $ar_result = array();
        $consulta = " SELECT id_estado, nombre AS estado FROM estado WHERE id_estado IN ('S', 'D', 'L', 'E', 'P', 'N', 'Z') ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function repeat_pedido($id_pedido, $id_sucursal){
        $temporal = "SELECT P.id_pedido FROM pedido P INNER JOIN sucursal S ON P.id_cliente = S.id_cliente WHERE S.id_sucursal = ".$id_sucursal." AND P.estado = 'T'";
        $if_query = $this->db->query($temporal);
        if ($if_query->num_rows() > 0){
            return "Pedido sin procesar, porfavor procese su pedido.";
        }
        else{
                $consulta = " SELECT PD.id_pedido_detalle AS id_pedido_detalle FROM pedido P "
                            . " INNER JOIN pedido_detalle PD  ON P.id_pedido =  PD.id_pedido "
                            . " INNER JOIN cliente_producto CP ON PD.id_producto = CP.id_producto "
                            . " INNER JOIN producto PR ON CP.id_producto = PR.id_producto "
                            . " WHERE P.id_pedido = ".$id_pedido." AND PR.estado = 'A'; ";
                $ar_consulta = $this->db->query($consulta);
                if ($ar_consulta->num_rows() > 0){
                    foreach ($ar_consulta->result() as $col){
                        $consulta2 =  " SELECT P.id_usuario AS id_usuario ,PD.id_tipo_precio AS id_tipo_precio, PD.id_producto AS id_producto, "
                                    . " PD.id_envase AS id_envase, PD.id_unidad AS id_unidad, PD.cantidad AS cantidad FROM pedido P "
                                    . " INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido WHERE PD.id_pedido_detalle = ".$col->id_pedido_detalle."; ";
                        $query = $this->db->query($consulta2);
                        $arrProcedure = array();
                        if ($query->num_rows() > 0) { 
                            foreach ($query->result() as $detail){ 
                                $arrProcedure = array(
                                    'P_id_sucursal'       => $id_sucursal,
                                    'P_id_usuario'        => $detail->id_usuario,
                                    'P_id_tipo_precio'    => $detail->id_tipo_precio,
                                    'P_id_producto'       => $detail->id_producto,
                                    'P_id_envase'         => $detail->id_envase,
                                    'P_id_unidad'         => $detail->id_unidad,
                                    'P_cantidad'          => $detail->cantidad
                                );
                                $this->db->query_sp('PRODUCTO_INSERT',$arrProcedure); 
                            }
                        }
                    }
                }
        }
    }
    
    public function get_idcategoria_pedido($id_pedido){
        $sql = "SELECT DISTINCT(SC.id_categoria) AS id_categoria FROM pedido_detalle PD
                INNER JOIN producto PR ON PD.id_producto = PR.id_producto
                INNER JOIN subcategoria_producto SP ON PR.id_producto = SP.id_producto
                INNER JOIN subcategoria SC ON SP.id_subcategoria = SC.id_subcategoria
                WHERE id_pedido = ".$id_pedido.";";
        $rs = $this->db->query($sql);
        if($rs->num_rows()){
            $row = $rs->row();
            return $row->id_categoria;
        }else{
            return 0;
        }
    }
}
