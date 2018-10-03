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
