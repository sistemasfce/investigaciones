<?php
 
class co_personas
{
    function get_personas($where='1=1')
    {
        $sql = "SELECT *,  
                    apellido || ', ' || nombres as nombre_completo
		FROM personas
		WHERE $where
		ORDER BY apellido, nombres
        ";
	return toba::db('plantadb')->consultar($sql);
    }

    function get_profesores($where='1=1')
    {
        $sql = "SELECT DISTINCT  personas.persona, apellido || ', ' || nombres as nombre_completo
		FROM personas LEFT OUTER JOIN designaciones ON personas.persona = designaciones.persona
                            LEFT OUTER JOIN asignaciones ON personas.persona = asignaciones.persona
		WHERE $where
                        AND designaciones.estado = 1 AND designaciones.categoria in (1,2,3)
                        AND asignaciones.estado = 1 AND asignaciones.responsable = 'S'
		ORDER BY nombre_completo
        ";
	return toba::db('plantadb')->consultar($sql);
    }
    
    function get_profesores_asignatura($ubicacion,$actividad)
    {
        $sql = "SELECT DISTINCT  personas.persona, apellido || ', ' || nombres as nombre_completo
		FROM personas LEFT OUTER JOIN designaciones ON personas.persona = designaciones.persona
                            LEFT OUTER JOIN asignaciones ON personas.persona = asignaciones.persona
		WHERE designaciones.estado = 1 AND designaciones.categoria in (1,2,3)
                        AND asignaciones.estado = 1 AND asignaciones.responsable = 'S'
                        AND asignaciones.ubicacion = $ubicacion 
                        AND  asignaciones.actividad = $actividad
		ORDER BY nombre_completo
        ";
	return toba::db('plantadb')->consultar($sql);
    }    
    
    function get_proponente_pii($ubicacion)
    {
        $sql = "SELECT DISTINCT  personas.persona, apellido || ', ' || nombres as nombre_completo
		FROM personas LEFT OUTER JOIN designaciones ON personas.persona = designaciones.persona
                      LEFT OUTER JOIN asignaciones ON personas.persona = asignaciones.persona      
		WHERE designaciones.estado = 1 AND designaciones.categoria in (1,2,3,4)
                        AND asignaciones.ubicacion = $ubicacion 
                       
		ORDER BY nombre_completo
        ";
	return toba::db('plantadb')->consultar($sql);
    }

    function get_proponente_pi($ubicacion)
    {
        $sql = "SELECT DISTINCT  personas.persona, apellido || ', ' || nombres as nombre_completo
		FROM personas LEFT OUTER JOIN designaciones ON personas.persona = designaciones.persona
                            LEFT OUTER JOIN asignaciones ON personas.persona = asignaciones.persona 
		WHERE designaciones.estado = 1 AND designaciones.categoria in (1,2,3)
                        AND asignaciones.ubicacion = $ubicacion 
                       
		ORDER BY nombre_completo
        ";
	return toba::db('plantadb')->consultar($sql);
    }
    
    function get_evaluadores($where='1=1')
    {
        $sql = "
            SELECT DISTINCT  
                personas.persona, 
                apellido || ', ' || nombres as nombre_completo
            FROM 
                personas 
                LEFT OUTER JOIN designaciones ON personas.persona = designaciones.persona
                LEFT OUTER JOIN personas_categorias_inv ON personas.persona = personas_categorias_inv.persona
            WHERE 
                $where
                AND designaciones.estado = 1 
                AND designaciones.categoria in (1,2,3)
                AND personas_categorias_inv.resultado_categoria in ('1 (uno)','2 (dos)','3 (tres)')
            ORDER BY 
                nombre_completo
        ";
	return toba::db('plantadb')->consultar($sql);
        
//        $where = '';
//        foreach ($datos as $dat)
//        {
//            $where.=$dat['persona'].',';
//        }
//        $where = trim($where, ',');
//        
//        $sql2 = "SELECT DISTINCT investigadores.persona, apellido || ', ' || nombres as nombre_completo
//                FROM investigadores 
//                        LEFT OUTER JOIN investigadores_categorias ON investigadores.investigador = investigadores_categorias.investigador
//                WHERE investigadores_categorias.resultado_categoria in (2,3,4)
//                        AND investigadores.persona in ($where)
//                ORDER BY nombre_completo
//                ";
//        return toba::db()->consultar($sql2);
        
        
    }
    
    function get_datos_persona($persona)
    {
        $sql = "SELECT *, 
                    date_part('year',age(fecha_nac)) as edad,
                    apellido || ', ' || nombres as nombre_completo
		FROM personas
		WHERE persona = $persona
        ";
	return toba::db('plantadb')->consultar_fila($sql);
    } 
    
    function get_datos_personas($personas)
    {
        $sql = "SELECT *, 
                    date_part('year',age(fecha_nac)) as edad,
                    apellido || ', ' || nombres as nombre_completo
		FROM personas
		WHERE persona in ($personas)
                ORDER BY persona
        ";
	return toba::db('plantadb')->consultar($sql);
    }        
}
?>
