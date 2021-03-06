<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Autorizarpedidos_m extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_autorizarpedidos_list($arrParam1,$arrParam2) {
        $ar_result = array();
        $consulta = "   SELECT      P.id_pedido AS id_pedido, DATE_FORMAT(P.fecha_registro, '%d/%m/%Y') AS fecha,
                                    LPAD(P.id_pedido,10,'0') AS codigo_pedido, C.nombre AS cliente, CONCAT(PE.nombre, ' ', PE.apellido) AS responsable,
                                    U.email AS email,  E.nombre AS estado
                        FROM        pedido P
                        INNER JOIN  cliente C ON P.id_cliente = C.id_cliente
                        INNER JOIN  sucursal S ON C.id_cliente  = S.id_cliente
                        INNER JOIN  sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
                        INNER JOIN  usuario U ON SU.id_usuario = U.id_usuario
                        INNER JOIN  persona_usuario PU ON U.id_usuario = PU.id_usuario
                        INNER JOIN  persona PE ON PU.id_persona = PE.id_persona
                        INNER JOIN  estado E ON P.estado = E.id_estado
                        WHERE       P.flag_autorizado = 0 AND P.estado NOT IN ('T') AND SU.id_usuario = ".$arrParam1." 
                        ORDER BY    P.fecha_registro DESC";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_cabecera_autorizarpedido_list($arrParam) {
        $ar_result = array();
        $consulta = "   SELECT 		LPAD(P.id_pedido,10,'0') AS codigo_pedido,
                                        DATE_FORMAT(fecha_registro, '%d/%m/%Y') AS fecha  
                        FROM 		pedido_detalle PD
                        INNER JOIN 	pedido P ON PD.id_pedido=P.id_pedido
                        INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
                        INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
                        INNER JOIN 	unidad U ON PD.id_unidad=U.id_unidad
                        INNER JOIN 	marca M ON PR.id_marca=M.id_marca
                        INNER JOIN      estado E ON P.estado = E.id_estado
                        WHERE           P.id_pedido = ".$arrParam."";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function get_detalle_autorizarpedidos_list($arrParam) {
        $ar_result = array();
        $consulta = "	SELECT 		PD.id_pedido_detalle, PD.id_pedido, PR.codigo AS codigo_producto, PR.nombre, 
                                        M.nombre AS marca, U.nombre AS unidad, PD.cantidad, PD.precio
                        FROM 		pedido_detalle PD
                        INNER JOIN 	pedido P ON PD.id_pedido=P.id_pedido
                        INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
                        INNER JOIN 	unidad U ON PD.id_unidad=U.id_unidad
                        INNER JOIN 	marca M ON PR.id_marca=M.id_marca
                        WHERE P.id_pedido = ".$arrParam."";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) { $ar_result = $query->result_array(); }
        return $ar_result;
    }
    
    public function authorize_pedido($arrParam){
        $ar_consulta = array();
        $consulta = " UPDATE pedido SET flag_autorizado = 1, estado = 'S' WHERE id_pedido = ".$arrParam."; ";
        $ar_consulta = $this->db->query($consulta);
        return 'Pedido Autorizado';
    }
}
