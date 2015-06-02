<?php

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Pedido_m extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    /*
     * Nombre: get_lista_categoria
     * Descripción: Devuelve una lista de las categorías asignadas a una sucursal.
     * Autor: Jhonathan Hernández
     * Fecha: 22/04/2014
     */
    public function get_lista_categoria($idsucursal){
        $data = array();
        $sql = "SELECT c.id_categoria,c.nombre,c.color
                FROM categoria c, corporativo_categoria cc, sucursal_categoria sc
                WHERE c.id_categoria=cc.id_categoria
                AND cc.id_corporativo_categoria=sc.id_corporativo_categoria
                AND id_sucursal=".$idsucursal;
        $rs = $this->db->query($sql);
        $i = 0;
        foreach($rs->result() as $item){
            $data[$i]['id_categoria']=$item->id_categoria;
            $data[$i]['nombre']=$item->nombre;
            $data[$i]['color']=$item->color;
            $data[$i]['monto_asignado']=$this->montos_generales($idsucursal,$item->id_categoria,'asignado');
            $data[$i]['monto_acumulado']=$this->get_monto_acumulado($idsucursal,$item->id_categoria);
            $data[$i]['total_pedidos']=$this->montos_generales($idsucursal,$item->id_categoria,'max_pedido');
            $data[$i]['pedidos_realizados']=$this->get_cantidad_pedido($idsucursal,$item->id_categoria);
            $data[$i]['fecha_cierre']=$this->montos_generales($idsucursal,$item->id_categoria,'fecha_cierre');
            $i++;
        }
        return $data;
    }
    
    /*
     * Nombre: get_lista_producto
     * Descripción: Devuelve una lista de las productos activos por categoría con los precios corporativos.
     * Autor: Jhonathan Hernández
     * Fecha: 22/04/2014
     */
    public function get_lista_producto($idcorporativo,$idcategoria){
        $sql = "SELECT DISTINCT(p.codigo) AS codigo,p.id_producto, CONCAT(p.nombre,' (',e.nombre,')') AS producto,
                e.nombre AS envase, u.nombre AS unidad, m.nombre AS marca, cp.precio AS precio
                FROM marca m, subcategoria s , subcategoria_producto sp, unidad u, envase e,
                producto p, cliente_producto cp, corporativo c
                WHERE m.id_marca=p.id_marca
                AND s.id_subcategoria=sp.id_subcategoria
                AND sp.id_producto=p.id_producto
                AND cp.id_unidad=u.id_unidad
                AND cp.id_envase=e.id_envase
                AND cp.id_producto=p.id_producto
                AND cp.id_cliente=c.id_cliente
                AND c.id_corporativo=$idcorporativo
                AND s.id_categoria=$idcategoria
                AND cp.estado='A'
                ORDER BY p.nombre";
        $query = $this->db->query($sql);
        return $query;
    }
    
    public function get_pedido() {
             $this->db->select('S.id_sucursal AS ID_SUCURSAL, C.id_cliente ID_CLIENTE, TI.nombre AS TIPO_IDENTIFICACION, C.documento AS DOCUMENTO, C.codigo_cliente AS CODIGO_CLIENTE, C.codigo_logistico AS CODIGO_LOGISTICO, C.nombre AS NOMBRE, C.direccion AS DIRECCION, D.nombre AS DISTRITO, C.referencia AS REFERENCIA');
             $this->db->from("cliente C");
             $this->db->join("tipo_identificacion TI "," C.id_tipo_identificacion = TI.id_tipo_identificacion","INNER" );
             $this->db->join("sucursal S ","C.id_cliente = S.id_cliente","INNER" );
             $this->db->join("distrito D ","C.id_distrito = D.id_distrito","INNER" );
             $this->db->where("C.estado","A");
             $consulta =  $this->db->get();
             foreach($consulta->result_array() as $fila)
                 echo json_encode($fila);
    }
    
    function get_producto_detalle($arrParam) {
        try {
            $arrResultado =  $this->db->query_sp('PRODUCTO_DETALLE_GET',$arrParam);
            return $arrResultado;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
        }
    }
    
    function add_producto($arrParam) {
        try {
            $arrResultado = $this->db->query_sp('PRODUCTO_INSERT',$arrParam);
            return $arrResultado;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado', 0, $e);
            return FALSE;
        }
    }
    
    function upd_producto($codigos,$cantidades,$idusuario) {
        try {
            $array_id = '';
            $id_pedido = $this->get_idpedido($idusuario);
            for($i=0;$i<count($codigos);$i++){
                $array_id.= $codigos[$i]['value'];
                if($i!=(count($codigos)-1))
                    $array_id.= ',';
                $sql = "UPDATE pedido_detalle SET cantidad='".$cantidades[$i]['value']."'
                        WHERE id_pedido=$id_pedido AND id_producto=".$codigos[$i]['value'];
                $this->db->query($sql);
            }
            $this->del_pedido_detalle($id_pedido,$array_id);
            $this->update_pedido_total($id_pedido);
        } catch (Exception $e) {
            throw new Exception('Error Inesperado', 0, $e);
            return FALSE;
        }
    }
    
    function del_pedido_detalle($idpedido,$codigo){
        $sql = "DELETE FROM pedido_detalle
                WHERE id_pedido=$idpedido
                AND id_producto NOT IN($codigo)";
        $this->db->query($sql);
    }
    
    /*
     * Nombre: del_pedido
     * Descripción: Elminar un pedido temporal
     * Autor: Jhonathan Hernández
     * Fecha: 23/04/2014
     */
    function del_pedido($idusuario){
        try {
            $id_pedido = $this->get_idpedido($idusuario);
            $sql = "DELETE FROM pedido_detalle WHERE id_pedido=$id_pedido";
            $rs = $this->db->query($sql);
            if($rs){
                $sql = "DELETE FROM pedido WHERE id_pedido=$id_pedido";
                $this->db->query($sql);
                $msg = 'El pedido se canceló satisfactoriamente';
            }else{
                $msg = 'Ocurrio un Error al intentar elminar el detalle';
            }
            return $msg;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado', 0, $e);
            return FALSE;
        }
    }
    
    function update_pedido_total($idpedido){
        $sql = "UPDATE pedido
                SET valor_venta=(SELECT SUM(cantidad*precio) FROM pedido_detalle WHERE id_pedido=$idpedido)
                WHERE id_pedido=$idpedido";
        $this->db->query($sql);
    }
    
    function get_idpedido($id_usuario){
        $sql = "SELECT MAX(id_pedido) AS id
                FROM pedido
                WHERE id_usuario=$id_usuario
                AND estado='T'";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        return $row->id;
    }
    
    public function get_idcategoria_pedido($id_sucursal){
        $sql = "SELECT cp.id_categoria AS idcategoria
                FROM corporativo_categoria cp, subcategoria sb, subcategoria_producto sp,
                producto r, pedido_detalle d, pedido p, sucursal s
                WHERE cp.id_corporativo=s.id_corporativo
                AND cp.id_categoria=sb.id_categoria
                AND sb.id_subcategoria=sp.id_subcategoria
                AND sp.id_producto=r.id_producto
                AND r.id_producto=d.id_producto
                AND d.id_pedido=p.id_pedido
                AND p.id_cliente=s.id_cliente
                AND s.id_sucursal=$id_sucursal
                AND p.estado='T'
                ORDER BY p.id_pedido DESC LIMIT 1";
        $rs = $this->db->query($sql);
        if($rs->num_rows()){
            $row = $rs->row();
            return $row->idcategoria;
        }else{
            return 0;
        }
    }
    
    function get_pedido_detalle($arrParam){
        try {
            $arrResultado =  $this->db->query_sp('PEDIDO_DETALLE_GET',$arrParam);
            return $arrResultado;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
        }
    }
    
    function confirm_pedido($arrParam) {
        try {
            $arrResultado = $this->db->query_sp('PEDIDO_CONFIRM',$arrParam);
            return $arrResultado;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado', 0, $e);
            return FALSE;
        }
    }
    
    /*
     * Nombre: get_monto_pedido
     * Descripción: Devuelve el monto de la compra temporal.
     * Autor: Jhonathan Hernández
     * Fecha: 24/04/2014
     */
    public function get_monto_pedido($arrParam){
        $spName = 'PEDIDO_SALDO_GET';
        $rs = $this->db->query_sp($spName,$arrParam);
        if(count($rs))
            return $rs[0]['IMPORTE'];
        else
            return 0;
    }
    
    /*
     * Nombre: get_montos_pedido
     * Descripción: Devuelve el monto total y la cantdiad de items de la compra temporal.
     * Autor: Jhonathan Hernández
     * Fecha: 24/04/2014
     */
    public function get_montos_pedido($arrParam){
        try {
            $sp = "PEDIDO_MONTOS_GET";
            $rs =  $this->db->query_sp($sp,$arrParam);
            $arrResultado = json_encode($rs);
            return $arrResultado;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
        }
    }
    
    /*
     * Nombre: get_nombre_categoria
     * Descripción: Devuelve el nombre de una categoría.
     * Autor: Jhonathan Hernández
     * Fecha: 22/04/2014
     */
    public function get_nombre_categoria($id){
        $sql = "SELECT nombre FROM categoria WHERE id_categoria=$id";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        return $row->nombre;
    }
    
    /*
     * Nombre: montos_generales
     * Descripción: Devuelve el valor de un monto (asignado,mínimo,max_pedido o fecha_cierre) según la categoría.
     * Autor: Jhonathan Hernández
     * Fecha: 22/04/2014
     */
    public function montos_generales($id_sucursal,$id_categoria,$monto){
        try {
            $array = array('asignado'=>'1','minimo'=>'2','max_pedido'=>'3','fecha_cierre'=>'4');
            $idmonto = $array[$monto];
            $sql = "SELECT valor FROM sucursal_categoria_monto
                    WHERE id_categoria=$id_categoria AND id_sucursal=$id_sucursal AND id_monto=$idmonto";
            $rs = $this->db->query($sql);
            if(!$rs->num_rows()){
                $sql = "SELECT ccm.valor AS valor
                        FROM sucursal_categoria sc, corporativo_categoria cc, corporativo_categoria_monto ccm, monto m
                        WHERE sc.id_categoria
                        AND sc.id_categoria=cc.id_categoria
                        AND sc.id_corporativo_categoria=cc.id_corporativo_categoria
                        AND cc.id_corporativo_categoria=ccm.id_corporativo_categoria
                        AND ccm.id_monto=m.id_monto
                        AND sc.id_sucursal=$id_sucursal
                        AND sc.id_categoria=$id_categoria
                        AND m.id_monto=$idmonto";
                $rs = $this->db->query($sql);
            }
            $row = $rs->row();
            return $row->valor;
        } catch (Exception $e) {
            throw new Exception('Error Inesperado');
        }
    }
    
    /*
     * Nombre: get_cantidad_pedido
     * Descripción: Devuelve la cantidad de pedidos por categoría que ha realizado una sucursal.
     * Autor: Jhonathan Hernández
     * Fecha: 22/04/2014
     */
    public function get_cantidad_pedido($id_sucursal,$id_categoria){
        $sql = "SELECT COUNT(DISTINCT(p.id_pedido)) AS cantidad
                FROM sucursal s, pedido p, pedido_detalle d, subcategoria_producto sp, subcategoria sc
                WHERE s.id_cliente=p.id_cliente
                AND p.id_pedido=d.id_pedido
                AND d.id_producto=sp.id_producto
                AND sp.id_subcategoria=sc.id_subcategoria
                AND s.id_sucursal=$id_sucursal
                AND sc.id_categoria=$id_categoria
                AND p.estado NOT IN('T','N')";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        return $row->cantidad;
    }
    
    /*
     * Nombre: get_monto_acumulado
     * Descripción: Devuelve el monto por categoría que ha gastado en compras una sucursal.
     * Autor: Jhonathan Hernández
     * Fecha: 22/04/2014
     */
    public function get_monto_acumulado($id_sucursal,$id_categoria){
        $sql = "SELECT monto_acumulado
                FROM sucursal_categoria
                WHERE id_sucursal=$id_sucursal
                AND id_categoria=$id_categoria";
        $rs = $this->db->query($sql);
        $row = $rs->row();
        return $row->monto_acumulado;
    }
    
    public function get_sucursales($arrParam){        
         $query = "SUCURSALES_GET";
         $arrCombo = $this->db->query_sp($query, $arrParam);
         $arrResultado = json_encode($arrCombo);
         return $arrResultado;
    }
       
}
