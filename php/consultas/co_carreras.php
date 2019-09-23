<?php
 
class co_carreras
{
    function get_carreras_ubicacion($ubicacion)
    {
        $sql = "SELECT  carreras.carrera,
                        carreras.nombre
		FROM carreras LEFT OUTER JOIN carreras_ubicaciones ON carreras.carrera = carreras_ubicaciones.carrera
		WHERE carreras_ubicaciones.ubicacion = $ubicacion
		ORDER BY nombre
        ";
	return toba::db('plantadb')->consultar($sql);
    }
   
    function get_asignaturas_departamento($carrera, $departamento)
    {
        $sql = "SELECT  actividades.actividad,
                        substring(actividades.descripcion,0,80) as nombre_corto,
                        actividades.descripcion
		FROM actividades LEFT OUTER JOIN carreras_actividades ON actividades.actividad = carreras_actividades.actividad
		WHERE carreras_actividades.carrera = $carrera AND actividades.departamento = $departamento
                ORDER BY actividades.descripcion
        ";
	return toba::db('plantadb')->consultar($sql);
    }       
    
    function get_departamentos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM departamentos
		WHERE $where AND departamento in (1,2,3,4,5,6,8)
        ";
	return toba::db('plantadb')->consultar($sql);
    }
    
    function get_carreras($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM carreras
		WHERE $where 
                ORDER BY nombre
        ";
	return toba::db('plantadb')->consultar($sql);
    }    

    function get_asignaturas($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *,
                        substring(actividades.descripcion,0,80) as nombre_corto
		FROM actividades
		WHERE $where AND ambito = 1
                ORDER BY nombre_corto
        ";
	return toba::db('plantadb')->consultar($sql);    
    }
    
    function get_ambitos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM ambitos
		WHERE $where 
        ";
	return toba::db('plantadb')->consultar($sql);    
    }
    
    function get_ciclos_lectivos($where=null)
    {
	if (!isset($where)) $where = '1=1';
        $sql = "SELECT *
		FROM ciclos_lectivos
		WHERE $where 
                ORDER BY ciclo_lectivo DESC
        ";
	return toba::db('plantadb')->consultar($sql);    
    }   
    
    function get_ciclos_lectivos_proyectos($where = null) 
    {
        if (!isset($where))
            $where = '1=1';
        $sql = "SELECT *
		FROM ciclos_lectivos
		WHERE $where 
                UNION SELECT date_part('year',CURRENT_DATE)+1
		UNION SELECT date_part('year',CURRENT_DATE)+2
		UNION SELECT date_part('year',CURRENT_DATE)+3
                ORDER BY ciclo_lectivo DESC
        ";
        return toba::db('plantadb')->consultar($sql);
    }

}
?>
