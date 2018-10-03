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
        $orden[] = 'apellido';
        $orden[] = 'nombres';
        $datos_ordenados = rs_ordenar_por_columnas($lista, $orden);
        return $datos_ordenados;
    }
    
    function get_investigadores_por_categoria($where)
    {
	$sql = "
		SELECT apellido || ', ' || nombres as nombre_completo,
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
	$sql = "SELECT evaluadores.*
		FROM evaluadores
		WHERE $where
                ORDER BY persona
		";
        // obtengo todos los evaluadores de la tabla ordenados por persona
	$datos_inv = toba::db()->consultar($sql);
        
        $where_array = '';
        // para cada evaluador armo un array para usar el where in ()
        // armo un diccionario con clave persona, valor evaluador
        foreach ($datos_inv as $dat) {
            $where_array = $where_array . $dat['persona'] . ',';
            $dict[$dat['persona']] = $dat['evaluador'];
            //$datos_per = toba::consulta_php('co_personas')->get_datos_persona($dat['persona']);
            //$aux['evaluador'] = $dat['evaluador'];
            //$aux['nombre_completo'] = $datos_per['nombre_completo'];               
            //$lista[] = $aux;
        }
        // quito la ultima coma
        $where_array = substr($where_array, 0, -1);
        // obtengo los nombres completos y persona
        $datos_per = toba::consulta_php('co_personas')->get_datos_personas($where_array);
        // armo el listado solo con evaluador sacado del diccionario y nombre completo
        foreach ($datos_per as $dp) {
            $aux['evaluador'] = $dict[$dp['persona']];
            $aux['nombre_completo'] = $dp['nombre_completo'];
            $lista[] = $aux;
        }
        $datos_ordenados = rs_ordenar_por_columna($lista, 'nombre_completo');
        return $datos_ordenados;
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