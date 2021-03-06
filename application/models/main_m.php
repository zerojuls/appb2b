<?php

if (!defined('BASEPATH'))
    exit('No permitir el acceso directo al script');

class Main_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_historial_compras($arrParam) {
        $data = $arrParam;
        $query = "HISTORICO_MAIN_GET";
        $arrCombo = $this->db->query_sp($query, $data);
        $arrResultado = json_encode($arrCombo, JSON_NUMERIC_CHECK);
        return $arrResultado;
    }

    public function get_productos_consumidos($arrParam) {
        $data = $arrParam;
        $query = "PRODUCTOS_CONSUMIDOS_GET";
        $arrCombo = $this->db->query_sp($query, $data);
        $arrResultado = json_encode($arrCombo, JSON_NUMERIC_CHECK);
        return $arrResultado;
    }

    public function get_pedido_categoria($arrParam1, $arrParam2) {
        $ar_result = array();
        $consulta = "   SELECT     
                        (CASE CA.id_categoria WHEN 1 THEN 'Oficina' WHEN 2 THEN 'Papeleria' WHEN 3 THEN 'Computo' WHEN 4 THEN 'Limpieza' WHEN 5 THEN 'Equipos' WHEN 6 THEN 'Kit' WHEN 7 THEN 'Alimentos' END) AS categoria , 
                        (SELECT COUNT(DISTINCT(P.id_pedido)) FROM pedido P
                        INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
                        INNER JOIN producto PR ON PD.id_producto = PR.id_producto
                        INNER JOIN subcategoria_producto SP ON PR.id_producto = SP.id_producto
                        INNER JOIN subcategoria SBC ON SP.id_subcategoria = SBC.id_subcategoria
                        WHERE P.id_usuario IN (SELECT id_usuario FROM cliente_usuario WHERE id_cliente IN (SELECT S1.id_cliente FROM sucursal_usuario SU1
                        INNER JOIN sucursal S1 ON SU1.id_sucursal = S1.id_sucursal
                        WHERE id_usuario = " . $arrParam1 . ")) 
                        AND	P.estado NOT IN ('T') AND SBC.id_categoria = CC.id_categoria) AS pedidos_categoria
                        FROM        monto M
                        INNER JOIN  corporativo_categoria_monto CCM ON M.id_monto = CCM.id_monto
                        INNER JOIN  corporativo_categoria CC ON CCM.id_corporativo_categoria = CC.id_corporativo_categoria
                        INNER JOIN  sucursal_categoria_monto SCM ON M.id_monto = SCM.id_monto
                        INNER JOIN  sucursal_categoria SC ON CCM.id_corporativo_categoria = SC.id_corporativo_categoria
                        INNER JOIN  categoria CA ON CA.id_categoria = CC.id_categoria
                        WHERE 		M.id_monto = 1
                        AND SC.id_sucursal IN (SELECT id_sucursal FROM sucursal_usuario SU1
                        WHERE id_usuario = " . $arrParam1 . ") 
                        GROUP BY CA.id_categoria";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) {
            $ar_result = $query->result_array();
        }
        return $ar_result;
    }

    public function get_saldos_disponibles($arrParam1, $arrParam2) {
        $ar_result = array();
        $consulta = "       SELECT      (CASE CA.id_categoria WHEN 1 THEN 'Oficina' WHEN 2 THEN 'Papeleria' WHEN 3 THEN 'Computo' WHEN 4 THEN 'Limpieza' WHEN 5 THEN 'Equipos' WHEN 6 THEN 'Kit' WHEN 7 THEN 'Alimentos' END) AS categoria, 
                                        (CASE WHEN ((SELECT COUNT(*) FROM sucursal_categoria_monto SCM INNER JOIN sucursal_usuario SU ON SCM.id_sucursal = SU.id_sucursal 
                                                     WHERE SU.id_usuario = " . $arrParam1 . " AND SCM.id_categoria = CC.id_categoria )>0) THEN (SCM.valor - SC.monto_acumulado)
                                              ELSE (CCM.valor - SC.monto_acumulado)
                                         END) AS saldo_disponible, S.id_sucursal, CU.id_usuario
                             FROM       monto M
                             INNER JOIN corporativo_categoria_monto CCM ON M.id_monto = CCM.id_monto
                             INNER JOIN corporativo_categoria CC ON CCM.id_corporativo_categoria = CC.id_corporativo_categoria
                             INNER JOIN sucursal_categoria_monto SCM ON M.id_monto = SCM.id_monto
                             INNER JOIN sucursal_categoria SC ON CCM.id_corporativo_categoria = SC.id_corporativo_categoria
                             INNER JOIN categoria CA ON CA.id_categoria = CC.id_categoria
                             INNER JOIN sucursal S ON SC.id_sucursal = S.id_sucursal
                             INNER JOIN cliente C ON S.id_cliente = C.id_cliente
                             INNER JOIN cliente_usuario CU ON C.id_cliente = CU.id_cliente
                             WHERE M.id_monto = 1
                             AND CU.id_usuario = " . $arrParam1 . " ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) {
            $ar_result = $query->result_array();
        }
        return $ar_result;
    }

    public function get_fecha_cierre($arrParam1, $arrParam2) {
        $ar_result = array();
        $consulta = "       SELECT    (CASE CA.id_categoria WHEN 1 THEN 'Oficina' WHEN 2 THEN 'Papeleria' WHEN 3 THEN 'Computo' WHEN 4 THEN 'Limpieza' WHEN 5 THEN 'Equipos' WHEN 6 THEN 'Kit' WHEN 7 THEN 'Alimentos' END) AS categoria, 
                                        (CASE 	WHEN ((	SELECT COUNT(*) FROM sucursal_categoria_monto SCM INNER JOIN sucursal_usuario SU ON SCM.id_sucursal = SU.id_sucursal 
							WHERE SU.id_usuario = " . $arrParam1 . " AND SCM.id_categoria = CC.id_categoria )>0) 
						THEN 	( CASE WHEN SCM.valor = 0  THEN DATE_FORMAT(LAST_DAY(NOW()),'%d/%m/%y') ELSE (SELECT CONCAT(SCM.valor,'/',DATE_FORMAT(NOW(),'%m/%y'))) END )
						ELSE 	( CASE WHEN CCM.valor = 0  THEN DATE_FORMAT(LAST_DAY(NOW()),'%d/%m/%y') ELSE (SELECT CONCAT(CCM.valor,'/',DATE_FORMAT(NOW(),'%m/%y'))) END )
					 END) AS fecha_cierre
                             FROM       monto M
                             INNER JOIN corporativo_categoria_monto CCM ON M.id_monto = CCM.id_monto
                             INNER JOIN corporativo_categoria CC ON CCM.id_corporativo_categoria = CC.id_corporativo_categoria
                             INNER JOIN sucursal_categoria_monto SCM ON M.id_monto = SCM.id_monto
                             INNER JOIN sucursal_categoria SC ON CCM.id_corporativo_categoria = SC.id_corporativo_categoria
                             INNER JOIN categoria CA ON CA.id_categoria = CC.id_categoria
                             INNER JOIN sucursal S ON SC.id_sucursal = S.id_sucursal
                             INNER JOIN cliente C ON S.id_cliente = C.id_cliente
                             INNER JOIN cliente_usuario CU ON C.id_cliente = CU.id_cliente
                             WHERE M.id_monto = 4
                             AND CU.id_usuario = " . $arrParam1 . " ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) {
            $ar_result = $query->result_array();
        }
        return $ar_result;
    }

    public function get_pedido_autorizados($arrParam1, $arrParam2) {
        $ar_result = array();
        $consulta = "   SELECT 	LPAD(P.id_pedido,10,'0') AS codigo_pedido,
				P.id_pedido AS id_pedido,
				'active' AS activo, C.nombre AS cliente, C.direccion AS direccion, 
				CA.nombre AS categoria,
				valor_venta AS monto, DATE_FORMAT(fecha_registro, '%d/%m/%Y') AS fecha
                        FROM pedido P
                        INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
                        INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
                        INNER JOIN producto PR ON PD.id_producto = PR.id_producto
                        INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
                        INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
                        INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
                        INNER JOIN cliente C ON P.id_cliente = C.id_cliente
                        INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
                        INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
                        WHERE P.flag_autorizado = 0 AND SU.id_usuario = " . $arrParam1 . " AND P.estado = 'Z'
                        GROUP BY PD.id_pedido
                        ORDER BY fecha";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) {
            $ar_result = $query->result_array();
        }
        return $ar_result;
    }

    public function get_pedido_pendientes($arrParam1, $arrParam2) {
        $ar_result = array();
        $consulta = "  SELECT       LPAD(P.id_pedido,10,'0') AS codigo_pedido,
                                    P.id_pedido AS id_pedido,
                                    'active' AS activo, C.nombre AS cliente, C.direccion AS direccion, 
                                    CA.nombre AS categoria,
                                    valor_venta AS monto, DATE_FORMAT(fecha_registro, '%d/%m/%Y') AS fecha, 
                                    E.nombre AS estado
                        FROM pedido P
                        INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
                        INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
                        INNER JOIN producto PR ON PD.id_producto = PR.id_producto
                        INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
                        INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
                        INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
                        INNER JOIN cliente C ON P.id_cliente = C.id_cliente
                        INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
                        INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
                        INNER JOIN estado E ON P.estado = E.id_estado
                        WHERE P.flag_autorizado = 1 AND SU.id_usuario = " . $arrParam1 . " AND P.estado = 'S'
                        GROUP BY PD.id_pedido
                        ORDER BY fecha";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) {
            $ar_result = $query->result_array();
        }
        return $ar_result;
    }

    public function get_montos_asignados($id_usuario, $alias_tipo_usuario) {
        $ar_result = array();
        $consulta = "SELECT     C.nombre AS sucursal,
                                        CA.nombre AS categoria,
                                        (CASE WHEN ((SELECT COUNT(*) FROM sucursal_categoria_monto SCM INNER JOIN sucursal_usuario SU ON SCM.id_sucursal = SU.id_sucursal 
                                                     WHERE SU.id_usuario = " . $id_usuario . " AND SCM.id_categoria = CC.id_categoria )>0) THEN SCM.valor
                                              ELSE CCM.valor
                                         END) AS monto_asignado,
                                        (CASE WHEN ((SELECT COUNT(*) FROM sucursal_categoria_monto SCM INNER JOIN sucursal_usuario SU ON SCM.id_sucursal = SU.id_sucursal 
                                                     WHERE SU.id_usuario = " . $id_usuario . " AND SCM.id_categoria = CC.id_categoria )>0) THEN (SCM.valor - SC.monto_acumulado)
                                              ELSE (CCM.valor - SC.monto_acumulado)
                                         END) AS monto_disponible, S.id_sucursal, CU.id_usuario
                             FROM       monto M
                             INNER JOIN corporativo_categoria_monto CCM ON M.id_monto = CCM.id_monto
                             INNER JOIN corporativo_categoria CC ON CCM.id_corporativo_categoria = CC.id_corporativo_categoria
                             INNER JOIN sucursal_categoria_monto SCM ON M.id_monto = SCM.id_monto
                             INNER JOIN sucursal_categoria SC ON CCM.id_corporativo_categoria = SC.id_corporativo_categoria
                             INNER JOIN categoria CA ON CA.id_categoria = CC.id_categoria
                             INNER JOIN sucursal S ON SC.id_sucursal = S.id_sucursal
                             INNER JOIN cliente C ON S.id_cliente = C.id_cliente
                             INNER JOIN cliente_usuario CU ON C.id_cliente = CU.id_cliente
                             WHERE M.id_monto = 1
                             AND CU.id_usuario = " . $id_usuario . " ";
        $query = $this->db->query($consulta);
        if ($query->num_rows() > 0) {
            $ar_result = $query->result_array();
        }
        return $ar_result;
    }

}
