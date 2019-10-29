<?php
 
class co_proyectos_inv
{
    function get_proyectos($where='1=1')
    {
        $sql = "SELECT *,
                    ubicaciones.descripcion as ubicacion_desc,
                    proyectos_estados.descripcion as estado_desc
		FROM proyectos_inv LEFT OUTER JOIN ubicaciones ON proyectos_inv.ubicacion = ubicaciones.ubicacion
                LEFT OUTER JOIN proyectos_estados ON proyectos_inv.estado = proyectos_estados.proyecto_estado
                WHERE $where
		ORDER BY ciclo_lectivo DESC, numero::Int
        ";
	$proyectos = toba::db()->consultar($sql);
        $total = array();
        
        foreach ($proyectos as $proy) {
            if (isset($proy['director'])) {
                $nombre = toba::consulta_php('co_personas')->get_datos_persona($proy['director']);
                $proy['director_nombre'] = $nombre['nombre_completo'];
            } else {
                $proy['director_nombre'] = '';
            }
            if (isset($proy['evaluador'])) {
                $nombre = toba::consulta_php('co_personas')->get_datos_persona($proy['evaluador']);
                $proy['evaluador_nombre'] = $nombre['nombre_completo'];
            } else {
                $proy['evaluador_nombre'] = '';
            }    
            if (isset($proy['carrera'])) {
                $where = 'carrera = '.$proy['carrera'];
                $nombre = toba::consulta_php('co_carreras')->get_carreras($where);
                $proy['carrera_desc'] = $nombre[0]['nombre'];
            } else {
                $proy['carrera_desc'] = '';
            }
            if (isset($proy['departamento'])) {
                $where = 'departamento = '.$proy['departamento'];
                $nombre = toba::consulta_php('co_carreras')->get_departamentos($where);
                $proy['departamento_desc'] = $nombre[0]['descripcion'];
            } else {
                $proy['departamento_desc'] = '';
            } 
            if (isset($proy['asignatura'])) {
                $where = 'actividad = '.$proy['asignatura'];
                $nombre = toba::consulta_php('co_carreras')->get_asignaturas($where);
                $proy['asignatura_desc'] = $nombre[0]['descripcion'];
            } else {
                $proy['asignatura_desc'] = '';
            } 
            $total[] = $proy;
        }
        return $total;
    }
    
    function get_ultimo_numero()
    {
        $sql = "SELECT numero FROM proyectos_inv ORDER BY proyecto DESC LIMIT 1";
        return toba::db()->consultar_fila($sql);
    }   
    
    function get_programas($where='1=1')
    {
        $sql = "SELECT *,
                    ubicaciones.descripcion as ubicacion_desc,
                    proyectos_estados.descripcion as estado_desc
		FROM proyectos_inv LEFT OUTER JOIN ubicaciones ON proyectos_inv.ubicacion = ubicaciones.ubicacion
                LEFT OUTER JOIN proyectos_estados ON proyectos_inv.estado = proyectos_estados.proyecto_estado
                WHERE $where AND ambito = 6
		ORDER BY ciclo_lectivo DESC, numero::Int
        ";
	$proyectos = toba::db()->consultar($sql);
        $total = array();
        
        foreach ($proyectos as $proy) {
            if (isset($proy['director'])) {
                $nombre = toba::consulta_php('co_personas')->get_datos_persona($proy['director']);
                $proy['director_nombre'] = $nombre['nombre_completo'];
            } else {
                $proy['director_nombre'] = '';
            }
            if (isset($proy['evaluador'])) {
                $nombre = toba::consulta_php('co_personas')->get_datos_persona($proy['evaluador']);
                $proy['evaluador_nombre'] = $nombre['nombre_completo'];
            } else {
                $proy['evaluador_nombre'] = '';
            }    
            if (isset($proy['carrera'])) {
                $where = 'carrera = '.$proy['carrera'];
                $nombre = toba::consulta_php('co_carreras')->get_carreras($where);
                $proy['carrera_desc'] = $nombre[0]['nombre'];
            } else {
                $proy['carrera_desc'] = '';
            }
            if (isset($proy['departamento'])) {
                $where = 'departamento = '.$proy['departamento'];
                $nombre = toba::consulta_php('co_carreras')->get_departamentos($where);
                $proy['departamento_desc'] = $nombre[0]['descripcion'];
            } else {
                $proy['departamento_desc'] = '';
            } 
            if (isset($proy['asignatura'])) {
                $where = 'actividad = '.$proy['asignatura'];
                $nombre = toba::consulta_php('co_carreras')->get_asignaturas($where);
                $proy['asignatura_desc'] = $nombre[0]['descripcion'];
            } else {
                $proy['asignatura_desc'] = '';
            } 
            $total[] = $proy;
        }
        return $total;
    }
    
    function get_proyectos_para_programa($where='1=1')
    {
        $sql = "SELECT *,
                    ubicaciones.descripcion as ubicacion_desc,
                    proyectos_estados.descripcion as estado_desc
		FROM proyectos_inv LEFT OUTER JOIN ubicaciones ON proyectos_inv.ubicacion = ubicaciones.ubicacion
                LEFT OUTER JOIN proyectos_estados ON proyectos_inv.estado = proyectos_estados.proyecto_estado
                WHERE tipo = 3 AND (ambito <> 6 or ambito is null) AND
                $where
		ORDER BY ciclo_lectivo DESC, numero::Int
        ";
	$proyectos = toba::db()->consultar($sql);
        $total = array();
        
        foreach ($proyectos as $proy) {
            if (isset($proy['director'])) {
                $nombre = toba::consulta_php('co_personas')->get_datos_persona($proy['director']);
                $proy['director_nombre'] = $nombre['nombre_completo'];
            } else {
                $proy['director_nombre'] = '';
            }
            $total[] = $proy;
        }
        return $total;
    }
}
