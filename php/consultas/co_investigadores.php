<?php
 
class co_investigadores
{
    function get_investigadores($where='1=1')
    {
        $sql = "SELECT *, 
			apellido || ', ' || nombres as nombre_completo
		FROM investigadores
		WHERE $where
		ORDER BY apellido, nombres
        ";
	return toba::db()->consultar($sql);
    }
    
    function get_investigadores_nombres($where='1=1')
    {
        $sql = "SELECT *, 
			apellido || ', ' || nombres as nombre_completo
		FROM investigadores
		WHERE $where
		ORDER BY apellido, nombres
        ";
	$datos_inv = toba::db()->consultar($sql);
        
        foreach ($datos_inv as $dat) {
            if ($dat['persona'] == '') { // si no tengo id persona me quedo con el nombre y apellido de tabla local
                $aux['investigador'] = $dat['investigador'];
                $aux['nombre_completo'] = $dat['nombre_completo'];
            } else { // busco el nombre en la tabla de plantadb
                $datos_per = toba::consulta_php('co_personas')->get_datos_persona($dat['persona']);
                $aux['investigador'] = $dat['investigador'];
                $aux['nombre_completo'] = $datos_per['nombre_completo'];               
            }
            $lista[] = $aux;
        }
        return $lista;
    }
    
    function get_investigadores_por_categoria($where)
    {
	$sql = "
		SELECT apellido || ', ' || nombres as nombre_completo,
		mail,
		(SELECT descripcion FROM categorias, investigadores_categorias WHERE categorias.categoria = investigadores_categorias.resultado_categoria
                	AND investigadores_categorias.investigador = investigadores.investigador ORDER BY resultado_anio DESC limit 1) as categoria_desc
		FROM investigadores_categorias LEFT OUTER JOIN investigadores ON (investigadores_categorias.investigador = investigadores.investigador)
		WHERE $where
		ORDER BY resultado_anio desc
		"; 
	return toba::db()->consultar($sql);
    }

    function get_investigadores_con_incentivo($where)
    {
	$sql = "
		SELECT apellido || ', ' || nombres as nombre_completo,
			mail,
			(SELECT descripcion FROM categorias, investigadores_categorias WHERE categorias.categoria = investigadores_categorias.resultado_categoria
                          AND investigadores_categorias.investigador = investigadores.investigador ORDER BY resultado_anio DESC limit 1) as categoria_desc
		FROM investigadores
		WHERE incentivo = 'S'
			AND $where
		ORDER BY nombre_completo
		";
	return toba::db()->consultar($sql);
    }

    function get_evaluadores($where='1=1')
    {
	$sql = "SELECT investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
			evaluadores.*
		FROM evaluadores, investigadores
		WHERE 	evaluadores.investigador = investigadores.investigador
			AND $where
		ORDER BY nombre_completo
		";
	$datos_inv = toba::db()->consultar($sql);
        
        foreach ($datos_inv as $dat) {
            if ($dat['persona'] == '') { // si no tengo id persona me quedo con el nombre y apellido de tabla local
                $aux['evaluador'] = $dat['evaluador'];
                $aux['nombre_completo'] = $dat['nombre_completo'];
            } else { // busco el nombre en la tabla de plantadb
                $datos_per = toba::consulta_php('co_personas')->get_datos_persona($dat['persona']);
                $aux['evaluador'] = $dat['evaluador'];
                $aux['nombre_completo'] = $datos_per['nombre_completo'];               
            }
            $lista[] = $aux;
        }
        return $lista;
    }   

    function get_evaluadores_nombres($where='1=1')
    {
	$sql = "SELECT investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
			evaluadores.*
		FROM evaluadores, investigadores
		WHERE 	evaluadores.investigador = investigadores.investigador
			AND $where
		ORDER BY nombre_completo
		";

	return toba::db()->consultar($sql);
    }   

    
    function get_copadece_investigadores($where)
    {
	$sql = "
		SELECT  investigadores.investigador,
                investigadores.documento,
                investigadores.apellido || ', ' || investigadores.nombres as nombre_completo,
                (SELECT descripcion FROM categorias, investigadores_categorias WHERE categorias.categoria = investigadores_categorias.resultado_categoria
                           AND investigadores_categorias.investigador = investigadores.investigador ORDER BY resultado_anio DESC limit 1) as categoria_desc,
		proyectos.titulo,
                proyectos.disciplina,
                proyectos.area,
                proyectos.tipo,
                CASE WHEN 
                    proyectos_estados.proyecto_estado in (10,16,17) THEN 'Finalizado'
                    ELSE 'En curso' 
                END as estado
            FROM proyectos LEFT OUTER JOIN investigadores ON (proyectos.director = investigadores.investigador)
                LEFT OUTER JOIN proyectos_estados ON (proyectos.proyecto_estado = proyectos_estados.proyecto_estado)
            WHERE $where
 
            ORDER BY nombre_completo, titulo

		";
	return toba::db()->consultar($sql);
    }     
}