<?php
 
class co_proyectos
{
    function get_proyectos($where='1=1')
    {
        $sql = "SELECT *,
			CASE WHEN prorroga_hasta is not null THEN prorroga_hasta
				ELSE fecha_final
			END as fecha_hasta,
                        (SELECT p2.titulo FROM proyectos as p2 LEFT OUTER JOIN proyectos_en_programas as pep ON p2.proyecto = pep.programa WHERE pep.proyecto = proyectos.proyecto) as programa_desc,
                        CASE WHEN numero_pi is not null THEN numero_pi || ' - ' || investigadores.apellido
                            ELSE titulo_corto
                        END as proyecto_desc,
			proyectos_estados.descripcion as estado_desc,
			ubicaciones.codigo as ubicacion_desc,
			investigadores.apellido || ', ' || investigadores.nombres as director_desc
		FROM proyectos LEFT OUTER JOIN proyectos_estados ON (proyectos.proyecto_estado = proyectos_estados.proyecto_estado)
			LEFT OUTER JOIN ubicaciones ON (proyectos.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN investigadores ON (proyectos.director = investigadores.investigador)
		WHERE $where
		ORDER BY denominacion,fecha_inicio::date, titulo
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_proyectos_catedras($where='1=1')
    {
        $sql = "SELECT *,
			proyectos_estados.descripcion as estado_desc,
			ubicaciones.codigo as ubicacion_desc,
			investigadores.apellido || ', ' || investigadores.nombres as director_desc
		FROM proyectos_catedras LEFT OUTER JOIN proyectos_estados ON (proyectos_catedras.proyecto_estado = proyectos_estados.proyecto_estado)
			LEFT OUTER JOIN ubicaciones ON (proyectos_catedras.ubicacion = ubicaciones.ubicacion)
			LEFT OUTER JOIN investigadores ON (proyectos_catedras.director = investigadores.investigador)
		WHERE $where
		ORDER BY denominacion,fecha_inicio::date, titulo
        ";
	return toba::db()->consultar($sql);
    }    

    function get_personas_por_proyecto($where)
    {
	$sql = "
		SELECT investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
			(SELECT descripcion FROM aptitudes WHERE aptitudes.aptitud = investigadores.aptitud) as aptitud_desc,
			(SELECT descripcion FROM categorias, investigadores_categorias WHERE categorias.categoria = investigadores_categorias.resultado_categoria
				AND investigadores_categorias.investigador = investigadores.investigador ORDER BY resultado_anio DESC limit 1) as categoria_desc,
			proyectos.titulo,
			proyectos.denominacion || ': ' || proyectos.titulo as titulo_desc,
			roles.descripcion as rol_desc
		FROM investigadores_en_proyectos 
			LEFT OUTER JOIN investigadores ON (investigadores_en_proyectos.investigador = investigadores.investigador)
			LEFT OUTER JOIN proyectos ON (investigadores_en_proyectos.proyecto = proyectos.proyecto)
			LEFT OUTER JOIN roles ON (investigadores_en_proyectos.rol = roles.rol)
		WHERE
			$where
UNION

                    SELECT dir.apellido || ', ' || dir.nombres as nombre_completo,
            (SELECT descripcion FROM aptitudes WHERE aptitudes.aptitud = dir.aptitud) as aptitud_desc,
            (SELECT descripcion FROM categorias, investigadores_categorias WHERE categorias.categoria = investigadores_categorias.resultado_categoria
                AND investigadores_categorias.investigador = dir.investigador ORDER BY resultado_anio DESC limit 1) as categoria_desc,
            proyectos.titulo,
	proyectos.denominacion || ': ' || proyectos.titulo as titulo_desc,
            'DIRECTOR' as rol_desc
        FROM proyectos 
            LEFT OUTER JOIN investigadores as dir ON (proyectos.director = dir.investigador)	            
        WHERE
                    $where
UNION

                    SELECT codir.apellido || ', ' || codir.nombres as nombre_completo,
            (SELECT descripcion FROM aptitudes WHERE aptitudes.aptitud = codir.aptitud) as aptitud_desc,
            (SELECT descripcion FROM categorias, investigadores_categorias WHERE categorias.categoria = investigadores_categorias.resultado_categoria
                AND investigadores_categorias.investigador = codir.investigador ORDER BY resultado_anio DESC limit 1) as categoria_desc,
            proyectos.titulo,
	proyectos.denominacion || ': ' || proyectos.titulo as titulo_desc,
            'CODIRECTOR' as rol_desc
        FROM proyectos 
            LEFT OUTER JOIN investigadores as codir ON (proyectos.codirector = codir.investigador)	            
        WHERE
                    $where
		";
	return toba::db()->consultar($sql);
    }

   function get_proyectos_por_evaluador($where)
   {
	$sql = "SELECT investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
			proyectos.titulo,
			proyectos.numero_pi,
			evaluadores_en_proyectos.*
		FROM evaluadores_en_proyectos 
			LEFT OUTER JOIN evaluadores ON (evaluadores_en_proyectos.evaluador = evaluadores.evaluador)
			LEFT OUTER JOIN investigadores ON (evaluadores.investigador = investigadores.investigador)
			LEFT OUTER JOIN proyectos ON (evaluadores_en_proyectos.proyecto = proyectos.proyecto)
		WHERE $where
		";
	return toba::db()->consultar($sql);
   }

   function get_proyectos_por_estado($where)
   {   
	$sql = "SELECT proyecto,
			titulo,
			numero_pi,
			investigadores.apellido || ', ' || investigadores.nombres as director_nombre
		FROM proyectos
			LEFT OUTER JOIN investigadores ON (proyectos.director = investigadores.investigador)
		WHERE $where
		ORDER BY titulo
		";    
	return toba::db()->consultar($sql);
   }

   function get_proyectos_por_persona($where)
   {
	$sql = "
		SELECT titulo, 
			titulo_corto,
			numero_pi,
                        denominacion,
                        investigadores.investigador,
                        investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
                        proyectos_estados.descripcion as estado_desc
		FROM proyectos LEFT OUTER JOIN investigadores_en_proyectos ON (proyectos.proyecto = investigadores_en_proyectos.proyecto)
                    LEFT OUTER JOIN investigadores ON (investigadores_en_proyectos.investigador = investigadores.investigador)
                    LEFT OUTER JOIN proyectos_estados ON (proyectos.proyecto_estado = proyectos_estados.proyecto_estado)
		WHERE $where
                    
                UNION

		SELECT titulo, 
			titulo as titulo_corto,
			numero_pi,
                        denominacion,
                        investigadores.investigador,
                        investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
                        proyectos_estados.descripcion as estado_desc
		        FROM proyectos_catedras LEFT OUTER JOIN investigadores_en_proyectos_catedras ON (proyectos_catedras.proyecto_catedra = investigadores_en_proyectos_catedras.proyecto_catedra)
                    LEFT OUTER JOIN investigadores ON (investigadores_en_proyectos_catedras.investigador = investigadores.investigador)
                    LEFT OUTER JOIN proyectos_estados ON (proyectos_catedras.proyecto_estado = proyectos_estados.proyecto_estado)
		WHERE $where
		";
	return toba::db()->consultar($sql);
   }

   function get_proyectos_por_anio($where)
   {
	$sql = "
		SELECT titulo, 
			titulo_corto,
			numero_pi,
			(SELECT extract (year FROM fecha_inicio)) as anio
		FROM proyectos
		ORDER BY anio
		";
	return toba::db()->consultar($sql);
   }

   function get_directores_en_curso($where)
   {
	$sql = "
		SELECT titulo, 
			titulo_corto,
			numero_pi,
			proyectos_estados.descripcion as estado_desc,
			investigadores.apellido || ', ' || investigadores.nombres as director_nombre,
			inv2.apellido || ', ' || inv2.nombres as codirector_nombre
		FROM proyectos LEFT OUTER JOIN investigadores ON (proyectos.director = investigadores.investigador)
			LEFT OUTER JOIN investigadores as inv2 ON (proyectos.codirector = inv2.investigador)
			LEFT OUTER JOIN proyectos_estados ON (proyectos.proyecto_estado = proyectos_estados.proyecto_estado)
		WHERE proyectos.proyecto_estado <> 10
		ORDER BY titulo
		";
	return toba::db()->consultar($sql);
   }
   
   function get_proyecto_en_programa($proyecto)
   {
       $sql = " SELECT titulo 
                FROM proyectos_en_programas 
                    LEFT OUTER JOIN proyectos ON proyectos_en_programas.programa = proyectos.proyecto
                WHERE proyectos_en_programas.proyecto = $proyecto
           ";
       return toba::db()->consultar_fila($sql);
   }

}
?>
