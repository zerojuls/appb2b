<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Supervisor_m extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_supervisor_list($id_corporativo) {
            $ar_result = array();
            $consulta = "   SELECT      CU.id_usuario, CONCAT(PE.nombre, ' ',PE.apellido) AS nombre_supervisor, TI.nombre AS tipo_documento, 
                                        PE.documento AS documento, PE.email AS email, PE.telefono1 AS telefono, C.nombre AS corporativo, E.nombre AS estado
                            FROM 	usuario U 
                            INNER JOIN 	cliente_usuario CU ON U.id_usuario = CU.id_usuario
                            INNER JOIN 	cliente C ON CU.id_cliente = C.id_cliente
                            INNER JOIN 	corporativo CO ON CU.id_cliente = CO.id_cliente
                            INNER JOIN  persona_usuario PU ON U.id_usuario = PU.id_usuario
                            INNER JOIN  persona PE ON PU.id_persona = PE.id_persona
                            INNER JOIN 	tipo_identificacion TI ON PE.id_tipo_identificacion = TI.id_tipo_identificacion
                            INNER JOIN 	estado E ON U.estado = E.id_estado
                            WHERE 	CO.id_corporativo  = ".$id_corporativo." AND U.id_tipo_usuario = 3 AND U.estado = 'A'; ";
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
}
