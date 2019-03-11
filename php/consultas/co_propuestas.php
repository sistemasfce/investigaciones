<?php
 
class co_propuestas
{
    function get_ultimo_numero()
    {
        $sql = "SELECT numero FROM propuestas WHERE numero <> '' ORDER BY propuesta DESC LIMIT 1";
        return toba::db()->consultar_fila($sql);
    }   

    function get_propuestas($where='1=1')
    {
	$sql = "SELECT *,
                        ubicaciones.descripcion as ubicacion_desc,
                        proyectos_tipos.descripcion as tipo_desc,
                        propuestas_estados.descripcion as estado_desc
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                LEFT OUTER JOIN proyectos_tipos ON propuestas.tipo = proyectos_tipos.proyecto_tipo
                LEFT OUTER JOIN propuestas_estados ON propuestas.estado = propuestas_estados.propuesta_estado
                WHERE $where 
                ORDER BY ciclo_lectivo, numero
        ";
	return toba::db()->consultar($sql);
    }    

}
?>
