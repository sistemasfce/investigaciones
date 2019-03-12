<?php
 
class co_propuestas
{
    function get_ultimo_numero()
    {
        $sql = "SELECT numero, ciclo_lectivo FROM propuestas WHERE numero <> '' ORDER BY propuesta DESC LIMIT 1";
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
    
    function get_pendientes_evaluador_por_carrera()
    {
        $sql = "SELECT  c1.ubicacion_desc, cant_cp, cant_lic_eco, cant_lic_adm, cant_tuc, cant_tuap, cant_tuab,
                        cant_tuaa, cant_tuac, cant_lic_tur
                FROM (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_cp
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 1
                GROUP BY ubicacion_desc) as c1

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_lic_eco
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 2
                GROUP BY ubicacion_desc) as c2 ON c1.ubicacion_desc = c2.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_lic_adm
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 3
                GROUP BY ubicacion_desc) as c3 ON c1.ubicacion_desc = c3.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_tuc
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 4
                GROUP BY ubicacion_desc) as c4 ON c1.ubicacion_desc = c4.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_tuap
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 6
                GROUP BY ubicacion_desc) as c5 ON c1.ubicacion_desc = c5.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_tuab
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 7
                GROUP BY ubicacion_desc) as c6 ON c1.ubicacion_desc = c6.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_tuaa
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 8
                GROUP BY ubicacion_desc) as c7 ON c1.ubicacion_desc = c7.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_tuac
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 9
                GROUP BY ubicacion_desc) as c8 ON c1.ubicacion_desc = c8.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_lic_tur
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND carrera = 10
                GROUP BY ubicacion_desc) as c9 ON c1.ubicacion_desc = c9.ubicacion_desc
        ";
        return toba::db()->consultar($sql);
    }

    function get_pendientes_evaluador_por_depto()
    {
        $sql = "SELECT  c1.ubicacion_desc, cant_contable, cant_derecho, cant_admin,
                        cant_economia, cant_matematica, cant_humanidades
                FROM (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_contable
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND departamento = 1
                GROUP BY ubicacion_desc) as c1

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_derecho
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND departamento = 2
                GROUP BY ubicacion_desc) as c2 ON c1.ubicacion_desc = c2.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_admin
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND departamento = 3
                GROUP BY ubicacion_desc) as c3 ON c1.ubicacion_desc = c3.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_economia
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND departamento = 4
                GROUP BY ubicacion_desc) as c4 ON c1.ubicacion_desc = c4.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_matematica
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND departamento = 5
                GROUP BY ubicacion_desc) as c5 ON c1.ubicacion_desc = c5.ubicacion_desc

                LEFT OUTER JOIN

                (SELECT ubicaciones.descripcion as ubicacion_desc, 
                        COUNT(*) as cant_humanidades
                FROM propuestas LEFT OUTER JOIN ubicaciones ON propuestas.ubicacion = ubicaciones.ubicacion
                WHERE estado = 1 AND tipo = 1 AND departamento = 6
                GROUP BY ubicacion_desc) as c6 ON c1.ubicacion_desc = c6.ubicacion_desc
        ";
        return toba::db()->consultar($sql);
    }    
}
?>
